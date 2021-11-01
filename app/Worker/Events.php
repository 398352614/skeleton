<?php
/**
 * 业务处理
 * User: long
 * Date: 2020/4/7
 * Time: 16:33
 */

namespace App\Worker;

use GatewayWorker\Lib\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Events extends BaseEvents
{

    public static function onWorkerStart($businessWorker)
    {
        self::init();
        Log::channel('worker')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . 'WorkerStart');
    }


    public static function onWebSocketConnect($clientId, $message)
    {
        try {
            //Log::channel('token--' . $message['get']['token'] ?? 'token null');
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
                    Log::channel('worker')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'clientId', [$clientId]);
                    return;
                };
            }
            Gateway::closeClient($clientId);
        } catch (\Exception $e) {
            Log::channel('worker')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        }
        return;
    }

    public static function onMessage($clientId, $message)
    {
        try {
            var_dump($message);
            $message = json_decode($message, true);
            if (json_last_error() !== 0 || empty($message['type']) || !in_array($message['type'], self::$type)) {
                Gateway::sendToClient($clientId, '消息格式不正确');
                return;
            }
            //若是心跳检测,则不执行业务
            if ($message['type'] == 'heart') return;
            //若推送至单个用户,to_id必须存在
            if ((stristr($message['type'], 'one') !== false) && empty($message['to_id'])) {
                Gateway::sendToClient($clientId, '接收人不存在');
                return;
            }
            //执行业务
            list($type, $data, $toId) = [$message['type'], json_encode($message['data'] ?? [], JSON_UNESCAPED_UNICODE), $message['to_id'] ?? null];
            is_null($toId) ? self::$type(Gateway::getSession($clientId), $data) : self::$type(Gateway::getSession($clientId), $data, $toId);
        } catch (\Exception $e) {
            Log::channel('worker')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        }
        return;
    }


    public static function onClose($clientId)
    {
        Log::channel('worker')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'closeClientId', [$clientId]);
    }

}
