<?php

namespace App\Notifications;

use App\Notifications\Channels\JPushChannel;
use App\Services\BaseConstService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
    public $timeout = 5;

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

    public function msgContent($locale)
    {
        return $content = __("线路[:line_name(:tour_no)],接收人[:fullname]取消预约，请前往站点列表刷新任务", ['line_name' => $this->batch['line_name'], 'tour_no' => $this->batch['tour_no'], 'fullname' => $this->batch['place_fullname']], $locale) . ';';
    }

    public function getMessage()
    {
        return [
            'title' => __('站点取消取派'),
            'extras' => [
                'type' => BaseConstService::PUSH_CANCEL_BATCH,
                'cn_content' => $this->msgContent('zh_CN'),
                'en_content' => $this->msgContent('en'),
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
            ->message(__('站点取消取派'), $this->getMessage())
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
