<?php

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\RechargeStatisticsResource;
use App\Models\RechargeStatistics;
use App\Services\BaseConstService;

use Illuminate\Support\Arr;

/**
 * Class MerchantService
 * @package App\Services\Admin
 */
class RechargeStatisticsService extends BaseService
{
    public $filterRules = [
    ];

    public function __construct(RechargeStatistics $rechargeStatistics)
    {
        parent::__construct($rechargeStatistics, RechargeStatisticsResource::class, RechargeStatisticsResource::class);
    }

    /**
     * 充值统计
     * @param $data
     * @return mixed
     * @throws BusinessLogicException
     */
    public function rechargeStatistics($data)
    {
        $where = ['driver_id' => auth()->user()->id, 'merchant_id' => $data['merchant_id'], 'tour_no' => $data['tour_no'], 'execution_date' => $data['execution_date']];
        $info = parent::getInfo($where, ['*'], false);
        if (empty($info)) {
            $where = array_merge($where, [
                'driver_name' => $data['driver_name'],
                'line_id' => $data['line_id'],
                'line_name' => $data['line_name']
            ]);
            $row = parent::create($where);
            if ($row == false) {
                throw new BusinessLogicException('纳入当日充值统计失败');
            }
            $info = $row->getAttributes();
        }
        $data['total_recharge_amount'] = $this->getRechargeService()->getList($where, ['*'], false)->sum('recharge_amount');
        $data['recharge_count'] = $this->getRechargeService()->getList(array_merge($where, ['status' => BaseConstService::RECHARGE_STATUS_3]), ['*'], false)->count();
        $row = parent::updateById($info['id'], Arr::only($data, ['total_recharge_amount', 'recharge_count']));
        if ($row == false) {
            throw new BusinessLogicException('纳入当日充值统计失败');
        }
        return $info['id'];
    }
}
