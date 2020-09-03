<?php
/**
 * 第三方对接 服务
 * User: long
 * Date: 2020/6/22
 * Time: 13:49
 */

namespace App\Services;


use App\Exceptions\BusinessLogicException;
use App\Models\Company;
use App\Models\Fee;
use App\Models\Merchant;
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
     * 批量新增
     * @param array $merchantId
     * @param int $postData
     * @param string $notifyType
     * @param string $content
     */
    public static function storeAll(int $merchantId, array $postData, string $notifyType, string $content)
    {
        $orderNoList = [];
        $dataList = [];
        switch ($notifyType) {
            case BaseConstService::NOTIFY_OUT_WAREHOUSE :
            case BaseConstService::NOTIFY_ACTUAL_OUT_WAREHOUSE:
                $orderNoList = array_column(array_column($postData['batch_list'], 'order_list'), 'order_no');
                break;
            case BaseConstService::NOTIFY_ASSIGN_BATCH:
                $orderNoList = array_column($postData['batch']['order_list'], 'order_no');
                break;
            case BaseConstService::NOTIFY_ORDER_CANCEL:
            case BaseConstService::NOTIFY_STORE_ORDER:
                $orderNoList[] = $postData['order_no'];
                break;
            default:
                $content = '';
                $orderNoList = [];
        }
        if (empty($content) || empty($orderNoList)) return;
        $now = now();
        $merchant = Merchant::query()->where('id', $merchantId)->first();
        if (empty($merchant)) return;
        foreach ($orderNoList as $orderNo) {
            $dataList[] = [
                'company_id' => $merchant->company_id,
                'merchant_id' => $merchantId,
                'order_no' => $orderNo,
                'content' => $content,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        ThirdPartyLog::query()->insert($dataList);
    }
}
