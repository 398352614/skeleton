<?php
/**
 * 第三方对接 服务
 * User: long
 * Date: 2020/6/22
 * Time: 13:49
 */

namespace App\Services;


use App\Exceptions\BusinessLogicException;
use App\Models\Fee;
use App\Models\Order;
use App\Models\ThirdPartyLog;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ThirdPartyLogService
 * @package App\Services
 * @property Order $orderModel
 */
class ThirdPartyLogService extends BaseService
{
    public $orderModel;

    public function __construct(ThirdPartyLog $model, $resource = null, $infoResource = null)
    {
        parent::__construct($model, $resource, $infoResource);
        $this->orderModel = new Order();
    }

    /**
     * 日志列表
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function index($id)
    {
        $order = $this->orderModel::query()->where('id', $id)->first();
        if (empty($order)) {
            throw new BusinessLogicException('订单不存在');
        }
        return parent::getList(['order_no' => $order->order_no], ['*'], false)->toArray();
    }


    /**
     * @param array $postData
     * @param int $merchantId
     * @param $notifyType
     * @param bool $pushStatus
     * @param string $msg
     */
    public static function storeAll(int $merchantId, array $postData, string $notifyType, $pushStatus = true, string $msg = '')
    {
        $content = '';
        $orderNoList = [];
        $dataList = [];
        switch ($notifyType) {
            case BaseConstService::NOTIFY_OUT_WAREHOUSE:
                $orderNoList = array_column(array_column($postData['batch_list'], 'order_list'), 'order_no');
                if ($pushStatus == true) {
                    $content = '出库推送成功';
                } else {
                    $content = '出库推送失败,失败原因:' . $msg;
                }
                break;
            case BaseConstService::NOTIFY_ASSIGN_BATCH:
                $orderNoList = array_column($postData['batch']['order_list'], 'order_no');
                if ($pushStatus == true) {
                    $content = '签收推送成功';
                } else {
                    $content = '签收推送失败,失败原因:' . $msg;
                }
                break;
            case BaseConstService::NOTIFY_ORDER_CANCEL:
                $orderNo = $postData['order_no'];
                if ($pushStatus == true) {
                    $content = '取消订单推送成功';
                } else {
                    $content = '取消订单推送成功,失败原因:' . $msg;
                }
                break;
            default:
                $content = '';
                $orderNoList = [];
        }
        if (empty($content) || empty($orderNoList)) return;
        foreach ($orderNoList as $orderNo) {
            $dataList[] = [
                'order_no' => $orderNo,
                'merchant_id' => $merchantId,
                'content' => $content
            ];
        }
        ThirdPartyLog::query()->insert($dataList);
    }
}
