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
use Illuminate\Support\Facades\Log;
use Workerman\Lib\Timer;

trait WorkerTrait
{
    /**
     * 解析数据
     *
     * @param $clientId
     * @param $message
     * @return bool|mixed
     */
    private static function parseData($message)
    {
        $message = json_decode($message, true);
        if (json_last_error() !== 0) {
            return false;
        }
        if (empty($message['type']) || !in_array($message['type'], self::$type)) {
            return false;
        }
        return $message;
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
     * 通知
     *
     * @param $clientId
     * @param $data
     */
    public static function notifyDriver($clientId, $data)
    {
        if (empty($data['u_id']) || empty($data['content'])) {
            Gateway::sendToClient($clientId, json_encode(ResponseTrait::response(1000, [], '数据不正确'), JSON_UNESCAPED_UNICODE));
            return;
        }
        $toUid = $data['u_id'];
        if (Gateway::isUidOnline($toUid)) {
            Gateway::sendToUid($toUid, $data['content'] ?? '');
        } else {
            Log::info('notify-driver-data:' . json_encode($data, true));
        }
    }

    /**
     * 向所有司机发送消息
     *
     * @param $clientId
     * @param $data
     */
    public static function notifyDriverList($clientId, $data)
    {
        Gateway::sendToGroup(Events::GUARD_DRIVER, $data['content']);
    }

    public static function notifyAdmin($clientId, $data)
    {
        Log::info('notifyAdmin-data' . json_encode($data, JSON_UNESCAPED_UNICODE));
        Gateway::sendToUid(Events::GUARD_ADMIN . '-' . $data['u_id'], $data['content']);
    }
}