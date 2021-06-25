<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/4/16
 * Time: 15:37
 */

namespace App\Worker;

use App\Models\Authenticatable;
use GatewayWorker\Lib\Gateway;
use Illuminate\Database\Connection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseEvents
{
    /**
     * @var Connection $db
     */
    public static $db;

    const GUARD_ADMIN = 'admin';

    const GUARD_DRIVER = 'driver';

    const GUARD_MERCHANT = 'merchant';

    const SUPER_ADMIN_ID = 3;

    /**
     * @var array 看守器列表
     * 键名 表名称
     * 键值 看守器名称
     */
    public static $guards = [
        'employee' => self::GUARD_ADMIN,
        'driver' => self::GUARD_DRIVER,
        'merchant' => self::GUARD_MERCHANT
    ];

    public static $type = ['heart', 'pushOneDriver', 'pushCompanyDriverList', 'pushDriverList', 'pushOneAdmin', 'pushCompanyAdminList', 'pushAdminList', 'pushAll'];

    protected static function init()
    {
        self::$db = DB::connection();
    }

    private static function getUid($guard, $id)
    {
        return $guard . '-' . $id;
    }

    private static function getCompanyGroup($guard, $companyId)
    {
        return $guard . '-' . $companyId;
    }

    /**
     * 设置用户信息
     * @param string $client_id
     * @param \Illuminate\Support\Facades\Auth $auth
     * @param string $guard
     * @param Authenticatable $user
     */
    protected static function setUser(string $client_id, $auth, string $guard, Authenticatable $user)
    {
        Gateway::bindUid($client_id, self::getUid($guard, $user->id));
        Gateway::joinGroup($client_id, $guard);
        Gateway::joinGroup($client_id, self::getCompanyGroup($guard, $user->company_id));
        $auth->unsetToken();
        $auth->setUserNull();
        Gateway::setSession($client_id, ['client_id' => $client_id, 'guard' => $guard, 'id' => $user->id, 'company_id' => $user->company_id]);
        return;
    }

    /**
     * 发送消息
     * @param $clientId
     */
    protected static function send($clientId)
    {
        $client = Gateway::getSession($clientId);
        $toId = Gateway::getUidByClientId($clientId);
        $list = self::$db->table('worker')->where('to_id', $toId)->get(['company_id', 'data', 'company_auth'])->toArray();
        if (empty($list)) return;
        foreach ($list as $message) {
            if (($message->company_auth == 1) && ($client['company_id'] !== $message->company_id)) continue;
            Gateway::sendToUid($toId, $message->data);
        }
        self::$db->table('worker')->where('to_id', $toId)->delete();
    }

    /**
     * 推送至用户
     * @param $client
     * @param $guard
     * @param $toId
     * @param $data
     */
    private static function pushOne($client, $guard, $toId, $data)
    {
        $toId = self::getUid($guard, $toId);
        $isOnline = Gateway::isUidOnline($toId);
        //若不在线,则直接写入数据库
        if (!$isOnline) {
            self::workerInsert($client, $toId, $data, 1);
            return;
        }
        //若在线,则直接推送消息
        $toClient = Gateway::getSession(Arr::first(Gateway::getClientIdByUid($toId)));
        if ($client['company_id'] != $toClient['company_id']) {
            Gateway::sendToClient($client['client_id'], '没有权限');
            return;
        }
        Gateway::sendToUid($toId, $data);
        return;
    }

    /**
     * 推送企业
     * @param $client
     * @param $guard
     * @param $data
     */
    private static function pushCompany($client, $guard, $data)
    {
        //推送数据至企业的司机组
        $group = self::getCompanyGroup($guard, $client['company_id']);
        Gateway::sendToGroup($group, $data);
        //若存在不在线的,则保存进数据库
        $toClientCount = Gateway::getClientCountByGroup($group);
        $dbClientCount = self::$db->table(array_search($guard, self::$guards))->where('company_id', $client['company_id'])->count(['id']);
        if ($toClientCount != $dbClientCount) {
            $toIdList = array_column(Gateway::getClientSessionsByGroup($group), 'id');
            $dbToIdList = self::$db->table(array_search($guard, self::$guards))->where('company_id', $client['company_id'])->pluck('id')->toArray();
            $NoToIdList = array_diff($dbToIdList, $toIdList);
            unset($toIdList, $dbToIdList);
            foreach ($NoToIdList as $toId) {
                self::workerInsert($client, self::getUid($guard, $toId), $data);
            }
            unset($NoToIdList);
        }
    }

    /**
     * 推送至看守器
     * @param $client
     * @param $guard
     * @param $data
     */
    private static function pushGuard($client, $guard, $data)
    {
        //只有超级管理员才能推送
        if (($client['guard'] != self::GUARD_ADMIN) || ($client['id'] != self::SUPER_ADMIN_ID)) {
            Gateway::sendToClient($client['client_id'], '没有权限');
            return;
        }
        //推送数据
        Gateway::sendToGroup($guard, $data);
        //若存在不在线的,则保存进数据库
        $toClientCount = Gateway::getClientCountByGroup($guard);
        $dbClientCount = self::$db->table(array_search($guard, self::$guards))->count(['id']);
        if ($toClientCount != $dbClientCount) {
            $toIdList = array_column(Gateway::getClientSessionsByGroup(array_search($guard, self::$guards)), 'id');
            $dbToIdList = self::$db->table(array_search($guard, self::$guards))->pluck('id')->toArray();
            $NoToIdList = array_diff($dbToIdList, $toIdList);
            unset($toIdList, $dbToIdList);
            foreach ($NoToIdList as $toId) {
                self::workerInsert($client, self::getUid($guard, $toId), $data);
            }
            unset($NoToIdList);
        }
    }

    /**
     * 推送消息至司机
     * @param $client
     * @param $toId
     * @param $data
     */
    public static function pushOneDriver($client, $data, $toId)
    {
        self::pushOne($client, self::GUARD_DRIVER, $toId, $data);
    }

    /**
     * 推送消息至企业内所有司机
     * @param $client
     * @param $data
     */
    public static function pushCompanyDriverList($client, $data)
    {
        self::pushCompany($client, self::GUARD_DRIVER, $data);
    }

    /**
     * 推送消息至所有司机消息
     * @param $client
     * @param $data
     */
    public static function pushDriverList($client, $data)
    {
        self::pushGuard($client, self::GUARD_DRIVER, $data);
    }

    /**
     * 通知管理员端员工
     * @param $client
     * @param $toId
     * @param $data
     */
    public static function pushOneAdmin($client, $data, $toId)
    {
        self::pushOne($client, self::GUARD_ADMIN, $toId, $data);
    }


    /**
     * 推送消息至企业内所有管理员
     * @param $clientId
     * @param $data
     */
    public static function pushCompanyAdminList($clientId, $data)
    {
        self::pushCompany($clientId, self::GUARD_ADMIN, $data);
    }


    //推送消息至所有管理员
    public static function pushAdminList($client, $data)
    {
        self::pushGuard($client, self::GUARD_ADMIN, $data);
    }

    /**
     * 推送所有用户
     * @param $client
     * @param $data
     * @throws \Exception
     */
    public static function pushAll($client, $data)
    {
        //只有超级管理员才能推送
        if (($client['guard'] != self::GUARD_ADMIN) || ($client['id'] != self::SUPER_ADMIN_ID)) {
            Gateway::sendToClient($client['client_id'], '没有权限');
            return;
        }
        //推送数据
        Gateway::sendToAll($data);
        //若存在不在线的,则保存进数据库
        $toClientCount = Gateway::getAllUidCount();
        $dbClientCount = 0;
        foreach (self::$guards as $table => $guard) {
            $count = self::$db->table($table)->count(['id']);
            $dbClientCount += $count;
        }
        if ($toClientCount != $dbClientCount) {
            $toIdList = Gateway::getAllUidList();
            $dbToIdList = [];
            foreach (self::$guards as $table => $guard) {
                $dbGuardIdList = self::$db->table($table)->pluck('id')->toArray();
                $dbGuardIdList = array_map(function ($id) use ($guard) {
                    return self::getUid($guard, $id);
                }, $dbGuardIdList);
                $dbToIdList = array_merge($dbToIdList, $dbGuardIdList);
            }
            $NoToIdList = array_diff($dbToIdList, $toIdList);
            unset($toIdList, $dbToIdList);
            foreach ($NoToIdList as $toId) {
                self::workerInsert($client, $toId, $data);
            }
            unset($NoToIdList);
        }
    }


    /**
     * 数据保存
     * @param $client
     * @param $toId
     * @param $data
     * @param int $companyAuth 是否需要验证公司权限1-是2-否
     */
    private static function workerInsert($client, $toId, $data, $companyAuth = 1)
    {
        $now = now();
        $insertData = [
            'company_id' => $client['company_id'],
            'to_id' => $toId,
            'data' => $data,
            'company_auth' => $companyAuth,
            'created_at' => $now,
            'updated_at' => $now
        ];
        $rowCount = self::$db->table('worker')->insert($insertData);
        if ($rowCount === false) {
            Log::channel('worker')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'insertData' . $insertData);
        }
    }

}
