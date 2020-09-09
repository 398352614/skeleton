<?php


namespace App\Services\Driver;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\RechargeInfoResource;
use App\Http\Resources\RechargeResource;
use App\Http\Resources\RechargeStatisticsResource;
use App\Models\Recharge;
use App\Models\RechargeStatistics;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;


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
     * 充值服务
     * @return RechargeService
     */
    public function getRechargeService()
    {
        return self::getInstance(RechargeService::class);
    }

    /**
     * 充值统计
     * @param $data
     * @return mixed
     * @throws BusinessLogicException
     */
    public function rechargeStatistics($data)
    {
        $where = ['driver_id' => auth()->user()->id, 'recharge_date' => $data['recharge_date'], 'merchant_id' => $data['merchant_id']];
        $info = parent::getInfo($where, ['*'], false);
        if (empty($info)) {
            $where = array_merge($where, ['driver_name' => auth()->user()->fullname, 'recharge_date' => Carbon::today()->format('Y-m-d')]);
            $row = parent::create($where);
            if ($row == false) {
                throw new BusinessLogicException('纳入当日充值统计失败');
            }
            $info = $row->getAttributes();
        }
        $data['total_recharge_amount'] = $this->getRechargeService()->getList($where, ['*'], false)->sum('recharge_amount');
        $data['recharge_count'] = $this->getRechargeService()->getList($where, ['*'], false)->count();
        $row = parent::updateById($info['id'], Arr::only($data, ['total_recharge_amount', 'recharge_count']));
        if ($row == false) {
            throw new BusinessLogicException('纳入当日充值统计失败');
        }
        return $info['id'];
    }
}
