<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\RechargeInfoResource;
use App\Http\Resources\Api\Admin\RechargeResource;
use App\Models\Recharge;
use App\Services\BaseConstService;

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
}
