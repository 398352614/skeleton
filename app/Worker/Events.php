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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Self_;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTGuard;
use Workerman\Lib\Timer;

class Events
{
    use WorkerTrait;

    const GUARD_ADMIN = 'admin';

    const GUARD_DRIVER = 'driver';

    const GUARD_MERCHANT = 'merchant';

    public static $guards = [self::GUARD_ADMIN, self::GUARD_DRIVER, self::GUARD_MERCHANT];

    public static $type = ['heart', 'notifyDriver', 'notifyDriverList', 'notifyAdmin'];

    public static function onWorkerStart($businessWorker)
    {
        echo "WorkerStart\n";
    }

    public static function onWebSocketConnect($client_id, $message)
    {
        if (empty($message['get']['token'])) {
            Gateway::closeClient($client_id);
            return;
        }
        $token = $message['get']['token'];
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $token);
        foreach (self::$guards as $guard) {
            $auth = Auth::guard($guard);
            $user = $auth->setRequest($request)->user();
            if (!empty($user)) {
                Gateway::bindUid($client_id, $guard . '-' . $user->id);
                Gateway::joinGroup($client_id, $guard);
                $auth->unsetToken();
                $auth->setUserNull();
                return;
            };
        }
        Gateway::closeClient($client_id);
        var_dump(Gateway::getAllGroupUidList());
        var_dump(Gateway::getAllUidList());
        return;
    }

    public static function onMessage($client_id, $message)
    {
        var_dump(Gateway::getAllGroupUidList());
        var_dump(Gateway::getAllUidList());
        //数据解析
        $message = self::parseData($message);
        if (!$message) {
            Gateway::sendToClient($client_id, '数据格式不正确');
            return;
        }
        //业务处理
        list($type, $data) = [$message['type'], $message['data'] ?? []];
        self::$type($client_id, $data);
    }


    public static function onClose($client_id)
    {
        echo var_dump("开始关闭client_id" . $client_id);
    }

}