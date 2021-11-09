<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2021/1/16
 * Time: 14:22
 */

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use JPush\Client as JPushClient;

class JPushChannel
{
    protected $client;

    public function __construct(JPushClient $jPushClient)
    {
        $this->client = $jPushClient;
    }

    /**
     * 发送指定的通知.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        /**@var JPushClient $push */
        $push = $notification->toJPush($notifiable, $this->client->push());
        $push->send();
//        try {
//            $push->send();
//        } catch (\JPush\Exceptions\APIConnectionException $e) {
//            Log::error('j-push-connection', ['message' => $e->getMessage()]);
//            exit;
//        } catch (\JPush\Exceptions\APIRequestException $e) {
//            Log::error('j-push-request', ['message' => $e->getMessage()]);
//            exit;
//        } catch (\Exception $e) {
//            Log::error('j-push-exception', ['message' => $e->getMessage()]);
//            exit;
//        }
    }
}
