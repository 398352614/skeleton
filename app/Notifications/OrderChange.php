<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use JPush\PushPayload;

class OrderChange extends Notification
{
    use Queueable;

    public $title;

    public $type;

    public $data;

    /**
     * OrderChange constructor.
     * @param $title
     * @param $type
     * @param array $data
     */
    public function __construct($type, $title = '', $data = [])
    {
        $this->title = $title;
        $this->type = $type;
        $this->data = $data;
    }

    public function getMessage()
    {
        return [
            'title' => $this->title,
            'extras' => [
                'type' => $this->type,
                'data' => $this->data
            ],
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'jpush'];
    }

    /**
     * Get the jpush representation of the notification.
     *
     * @param mixed $notifiable
     * @return PushPayload
     */
    public function toJPush($notifiable, PushPayload $payload)
    {
        return $payload
            ->setPlatform('all')
            ->addRegistrationId($notifiable->id)
            ->message('订单推送', $this->getMessage())
            ->options(['apns_production' => config('j-push.production')]);
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
