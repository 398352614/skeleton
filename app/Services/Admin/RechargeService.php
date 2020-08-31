<?php


namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\RechargeInfoResource;
use App\Http\Resources\RechargeResource;
use App\Models\Recharge;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
use Illuminate\Support\Carbon;


/**
 * Class MerchantService
 * @package App\Services\Admin
 */
class RechargeService extends BaseService
{
    public $filterRules = [
        'merchant_id' => ['like', 'merchant_id'],
        'status' => ['=', 'status'],
        'verify_status' => ['=', 'verify_status'],
        'recharge_no' => ['=', 'recharge_no'],
        'out_user_id' => ['=', 'out_user_id'],
        'driver_name,out_user_id' => ['like', 'key_word'],
        'recharge_date' => ['between', ['begin_date', 'end_date']],
    ];

    public function __construct(Recharge $recharge)
    {
        parent::__construct($recharge, RechargeResource::class, RechargeInfoResource::class);
    }

    /**
     * @return MerchantService
     */
    public function getMerchantService()
    {
        return self::getInstance(MerchantService::class);
    }

    /**
     * @return OrderNoRuleService
     */
    public function getOrderNoRuleService()
    {
        return self::getInstance(OrderNoRuleService::class);
    }

    /**
     * 充值列表
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPageList()
    {
        $this->query->where(['status'=>BaseConstService::RECHARGE_STATUS_3])->orderBy('recharge_time');
        return parent::getPageList();
    }

    /**
     * 充值查询
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id]);
        if(empty($info)){
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }

    /**
     * 审核
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function verify($id, $params)
    {
        $info = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (floatval($params['verify_recharge_amount']) > floatval($info['recharge_amount'])) {
            throw new BusinessLogicException('实际金额不能大于充值金额');
        }
        if ($info['verify_status'] == BaseConstService::RECHARGE_VERIFY_STATUS_2) {
            throw new BusinessLogicException('该充值已审核');
        }
        $row = parent::updateById($id, [
            'verify_recharge_amount' => $params['verify_recharge_amount'],
            'verify_remark' => $params['verify_remark'],
            'verify_status' => BaseConstService::RECHARGE_VERIFY_STATUS_2,
            'verify_date'=>Carbon::today()->format('Y-m-d'),
            'verify_time'=>now()
        ]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        return;
    }
}
