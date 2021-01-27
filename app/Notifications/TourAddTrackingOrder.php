<?php

namespace App\Notifications;

use App\Models\TrackingOrderMaterial;
use App\Models\TrackingOrderPackage;
use App\Notifications\Channels\JPushChannel;
use App\Services\BaseConstService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JPush\PushPayload;

class TourAddTrackingOrder extends Notification implements ShouldQueue
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

    public $trackingOrderList;

    public $dbBatchList;

    /**
     * TourAddTrackingOrder constructor.
     * @param array $trackingOrderList
     * @param array $dbBatchList
     */
    public function __construct(array $trackingOrderList, array $dbBatchList)
    {
        $this->trackingOrderList = $trackingOrderList;
        $this->dbBatchList = $dbBatchList;
    }

    public function msgContent()
    {
        return $this->getAddFullName();
    }

    public function getMessage()
    {
        return [
            'title' => '线路加单',
            'extras' => [
                'type' => BaseConstService::PUSH_TOUR_ADD_ORDER,
                'data' => $this->getData()
            ],
        ];
    }

    private function getAddFullName()
    {
        $trackingOrderFullNameList = array_column($this->trackingOrderList, 'place_fullname');
        $batchFullNameList = array_column($this->dbBatchList, 'place_fullname');
        $fullNameList = array_diff($trackingOrderFullNameList, $batchFullNameList);
        if (empty($fullNameList)) {
            return '未新增站点';
        }
        $count = count($fullNameList);
        $fullNameList = implode(',', $fullNameList);
        return "已追加{$count}个站点,收件人[{$fullNameList}],请前往站点列表手动调度派送顺序";
    }

    public function getData()
    {
        return [
            'is_exist_special_remark' => !empty(array_filter(array_column($this->trackingOrderList, 'special_remark'))) ? true : false,
            'tracking_order_list' => $this->getTrackingOrderPackageList($this->trackingOrderList),
            'material_list' => $this->getMaterialList($this->trackingOrderList),
        ];
    }

    /**
     * 获取订单包裹列表
     * @param $trackingOrderList
     * @return mixed
     */
    private function getTrackingOrderPackageList($trackingOrderList)
    {
        $packageList = TrackingOrderPackage::query()->whereIn('tracking_order_no', array_column($trackingOrderList, 'tracking_order_no'))->get(['order_no', 'tracking_order_no', 'type', 'name', 'express_first_no', 'expect_quantity'])->toArray();
        $packageList = collect($packageList)->groupBy('order_no')->toArray();
        foreach ($trackingOrderList as &$trackingOrder) {
            $trackingOrder['package_list'] = $packageList[$trackingOrder['order_no']] ?? '';
            $trackingOrder = Arr::only($trackingOrder, ['order_no', 'tracking_order_no', 'special_remark', 'package_list']);
        }
        unset($packageList);
        return $trackingOrderList;
    }

    /**
     * 获取材料列表
     * @param $trackingOrderList
     * @return array
     */
    private function getMaterialList($trackingOrderList)
    {
        $query = TrackingOrderMaterial::query();
        $materialList = $query
            ->whereIn('tracking_order_no', array_column($trackingOrderList, 'tracking_order_no'))
            ->groupBy('code')
            ->get(['name', 'code', DB::raw('SUM(expect_quantity) as expect_quantity'), DB::raw('0 as actual_quantity')])
            ->toArray();
        $materialList = Arr::where($materialList, function ($material) {
            return !empty($material['code']) && !empty($material['expect_quantity']);
        });
        return $materialList;
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
