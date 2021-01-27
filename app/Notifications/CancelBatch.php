<?php

namespace App\Notifications;

use App\Models\TrackingOrderMaterial;
use App\Models\TrackingOrderPackage;
use App\Notifications\Channels\JPushChannel;
use App\Services\BaseConstService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use JPush\PushPayload;

class CancelBatch extends Notification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * 任务连接名称。
     *
     * @var string|null
     */
    public $connection = 'redis';

    /**
     * 任务发送到的队列的名称.
     *
     * @var string|null
     */
    public $queue = 'notification-push';

    /**
     * 任务可以执行的最大秒数 (超时时间)。
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

    /**
     * 延迟时间
     *
     * @var int
     */
    public $delay = 30;

    public $batch;

    /**
     * TourAddTrackingOrder constructor.
     * @param array $batch
     */
    public function __construct(array $batch)
    {
        $this->batch = $batch;
    }

    public function msgContent()
    {
        return "接收人[{$this->batch['place_fullname']}]取消预约，请前往站点列表刷新任务";
    }

    public function getMessage()
    {
        return [
            'title' => "站点取消取派",
            'extras' => [
                'type' => BaseConstService::PUSH_CANCEL_BATCH,
                'data' => $this->getData()
            ],
        ];
    }

    public function getData()
    {
        return $this->batch;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [JPushChannel::class];
    }

    /**
     * Get the jpush representation of the notification.
     *
     * @param mixed $notifiable
     * @param PushPayload $payload
     * @return PushPayload
     */
    public function toJPush($notifiable, PushPayload $payload)
    {
        return $payload
            ->setPlatform('all')
            //->addRegistrationId((string)($notifiable->id))
            ->addAlias((string)($notifiable->id))
            ->androidNotification($this->msgContent(), $this->getMessage())
            ->message($this->msgContent(), $this->getMessage())
            ->options(['apns_production' => config('jpush.production')]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
