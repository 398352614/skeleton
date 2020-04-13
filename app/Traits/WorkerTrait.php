<?php

/**
 * worker trait
 * User: long
 * Date: 2020/4/7
 * Time: 18:12
 */

namespace App\Traits;

use App\Worker\Events;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Workerman\Lib\Timer;

trait WorkerTrait
{
    /**
     * 解析消息
     * @param $message
     * @return bool|mixed
     */
    private static function parseMessage($message)
    {
        $message = json_decode($message, true);
        var_dump($message);
        if (json_last_error() !== 0) {
            return false;
        }
        if (empty($message['type']) || !in_array($message['type'], self::$type)) {
            return false;
        }
        return $message;
    }

    /**
     * 解析数据
     * @param $data
     * @return bool
     */
    private static function parseData($data)
    {
        if (empty($data['u_id']) || empty($data['content'])) {
            return false;
        }
        return true;
    }

    /**
     * 心跳检测
     * @param $client_id
     * @param $data
     */
    public static function heart($client_id, $data)
    {
        return;
    }

    /**
     * 通知司机
     * @param $clientId
     * @param $data
     * @return bool
     */
    public static function notifyDriver($clientId, $data)
    {
        $bool = self::parseData($data);
        if (!$bool) {
            return false;
        }
        $toUid = Events::GUARD_DRIVER . '-' . $data['u_id'];
        if (Gateway::isUidOnline($toUid)) {
            Gateway::sendToUid($toUid, $data['content'] ?? '');
        } else {
            Log::info('notify-data' . json_encode($data, JSON_UNESCAPED_UNICODE));
        }
        return true;
    }

    /**
     * 向所有司机发送消息
     * @param $clientId
     * @param $data
     */
    public static function notifyDriverList($clientId, $data)
    {
        $uIdList = Gateway::getUidListByGroup(Events::GUARD_DRIVER);
        Log::info('notify-driver-list' . json_encode($uIdList, JSON_UNESCAPED_UNICODE));
        Gateway::sendToGroup(Events::GUARD_DRIVER, $data['content']);
    }

    /**
     * 通知管理员端员工
     * @param $clientId
     * @param $data
     * @return bool
     */
    public static function notifyAdmin($clientId, $data)
    {
        $bool = self::parseData($data);
        if (!$bool) {
            return false;
        }
        $toUid = Events::GUARD_ADMIN . '-' . $data['u_id'];
        if (Gateway::isUidOnline($toUid)) {
            Gateway::sendToUid($toUid, $data['content']);
        } else {
            Log::info('notify-admin-list' . json_encode($data, JSON_UNESCAPED_UNICODE));
        }
        return true;
    }

}