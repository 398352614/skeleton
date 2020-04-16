<?php
/**
 * 业务处理
 * User: long
 * Date: 2020/4/7
 * Time: 16:33
 */

namespace App\Worker;

use App\Models\Employee;
use App\Traits\WorkerTrait;
use GatewayWorker\Lib\Gateway;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Self_;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTGuard;
use Workerman\Lib\Timer;

class Events
{
    use WorkerTrait;

    /**
     * @var Connection $db
     */
    public static $db;

    const GUARD_ADMIN = 'admin';

    const GUARD_DRIVER = 'driver';

    const GUARD_MERCHANT = 'merchant';

    const SUPER_ADMIN_ID = 1;

    public static $guards = [self::GUARD_ADMIN, self::GUARD_DRIVER, self::GUARD_MERCHANT];

    public static $type = ['heart', 'pushDriver', 'pushDriverList', 'pushAdmin'];

    public static function onWorkerStart($businessWorker)
    {
        self::init();
        echo "WorkerStart\n";
    }


    public static function onWebSocketConnect($clientId, $message)
    {
        if (empty($message['get']['token'])) {
            Gateway::closeClient($clientId);
            return;
        }
        $token = $message['get']['token'];
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $token);
        foreach (self::$guards as $guard) {
            $auth = Auth::guard($guard);
            $user = $auth->setRequest($request)->user();
            if (!empty($user)) {
                self::setUser($clientId, $auth, $guard, $user);
                self::send($clientId);
                return;
            };
        }
        Gateway::closeClient($clientId);
        return;
    }

    public static function onMessage($clientId, $message)
    {
        var_dump($message);
        $message = json_decode($message, true);
        if (json_last_error() == 0 || empty($message['type']) || !in_array($message['type'], self::$type)) {
            Gateway::sendToClient($clientId, '消息格式不正确');
            return;
        }
        if ((stristr($message['type'], 'one') !== false) && empty($message['to_id'])) {
            Gateway::sendToClient($clientId, '接收人不存在');
            return;
        }
        list($type, $data, $toId) = [$message['type'], $message['data'] ?? [], $message['to_id'] ?? null];
        is_null($toId) ? self::$type(Gateway::getSession($clientId), $data) : self::$type(Gateway::getSession($clientId), $data, $toId);
    }


    public static function onClose($clientId)
    {
        Log::info("开始关闭client_id" . $clientId);
    }

}