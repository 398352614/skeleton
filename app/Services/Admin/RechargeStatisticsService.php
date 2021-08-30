<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\RechargeStatisticsResource;
use App\Models\RechargeStatistics;
use App\Services\BaseConstService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class MerchantService
 * @package App\Services\Admin
 */
class RechargeStatisticsService extends BaseService
{
    public $filterRules = [
        'merchant_id' => ['like', 'merchant_id'],
        'status' => ['=', 'status'],
        'driver_name' => ['like', 'key_word'],
        'recharge_date' => ['between', ['begin_date', 'end_date']],
    ];

    public function __construct(RechargeStatistics $rechargeStatistics)
    {
        parent::__construct($rechargeStatistics, RechargeStatisticsResource::class, RechargeStatisticsResource::class);
    }

    /**
     * 充值列表
     * @return Collection
     */
    public function getPageList()
    {
        $this->query->orderByDesc('execution_date');
        return parent::getPageList();
    }

    /**
     * 充值查询
     * @param $id
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info['recharge_list'] = $this->getRechargeService()->getList(['recharge_statistics_id' => $id,'status'=>BaseConstService::RECHARGE_STATUS_3]);
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
        if ($info['recharge_date'] == Carbon::today()->format('Y-m-d')) {
            throw new BusinessLogicException('当日充值未完结，请次日审核');
        }
        if (floatval($params['verify_recharge_amount']) > floatval($info['total_recharge_amount'])) {
            throw new BusinessLogicException('实际金额不能大于预计金额');
        }
        if ($info['status'] == BaseConstService::RECHARGE_VERIFY_STATUS_2) {
            throw new BusinessLogicException('该充值已审核,请勿重复审核');
        }
        $row = parent::updateById($id, [
            'verify_recharge_amount' => $params['verify_recharge_amount'],
            'verify_remark' => $params['verify_remark'],
            'status' => BaseConstService::RECHARGE_VERIFY_STATUS_2,
            'verify_date' => Carbon::today()->format('Y-m-d'),
            'verify_time' => now(),
            'verify_name' =>auth()->user()->username
        ]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        return;
    }
}
