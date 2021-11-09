<?php
/**
 * 货主组列表 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\MerchantGroupResource;
use App\Models\MerchantGroup;
use App\Models\MerchantGroupFeeConfig;
use App\Services\BaseConstService;
use Illuminate\Support\Arr;

/**
 * Class MerchantGroupService
 * @package App\Services\Admin
 * @property MerchantGroupFeeConfig $merchantGroupFeeConfigModel
 */
class MerchantGroupService extends BaseService
{
    public $filterRules = [
        'name' => ['like', 'name']
    ];

    protected $merchantGroupFeeConfigModel;

    public function __construct(MerchantGroup $merchantGroup)
    {
        parent::__construct($merchantGroup, MerchantGroupResource::class);
        $this->merchantGroupFeeConfigModel = new MerchantGroupFeeConfig();
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        //获取费用列表
        $feeCodeList = $this->merchantGroupFeeConfigModel->newQuery()->where('merchant_group_id', $info['id'])->pluck('fee_code')->toArray();
        if (empty($feeCodeList)) {
            $info['fee_list'] = [];
        } else {
            $feeList = $this->getFeeService()->getList(['code' => ['in', $feeCodeList]], ['id', 'code', 'name'], false)->toArray();
            $info['fee_list'] = $feeList;
        }
        return $info;
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->check($params);
        $merchantGroup = parent::create($params);
        if ($merchantGroup === false) {
            throw new BusinessLogicException('新增失败，请重新操作');
        }
        $merchantGroupId = $merchantGroup->getAttribute('id');
        //新增货主所有线路范围
        $this->getLineService()->storeAllPostCodeLineRangeByMerchantGroupId($merchantGroupId);
    }

    /**
     * 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $this->check($data, $id);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $info = $this->getMerchantService()->getInfo(['merchant_group_id' => $id], ['*'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('当前货主组内还有成员,不能删除');
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //删除货主组的线路数据
        $rowCount = $this->getMerchantGroupLineRangeService()->delete(['merchant_group_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }

    /**
     * 验证
     * @param $params
     * @param $id
     * @throws BusinessLogicException
     */
    private function check(&$params, $id = null)
    {
        $transportPrice = $this->getTransportPriceService()->getInfo(['id' => $params['transport_price_id']], ['*'], false);
        if (empty($transportPrice)) {
            throw new BusinessLogicException('运价不存在');
        }
        $transportPrice=$transportPrice->toArray();
        if($transportPrice['status'] == BaseConstService::NO){
            throw new BusinessLogicException('运价已被禁用');
        }
        //若设置当前为默认的,则原来默认的设置为否
        if (intval($params['is_default']) === 1) {
            $where = empty($id) ? [] : ['id' => ['<>', $id]];
            $where = Arr::add($where, 'is_default', 1);
            $rowCount = parent::update($where, ['is_default' => 2]);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败，请重新操作');
            }
        }
    }


    /**
     * 获取费用列表
     * @param null $merchantGroupId
     * @return array
     */
    public function getFeeList($merchantGroupId = null)
    {
        $feeList = $this->getFeeService()->getList([], ['id', 'code', 'name'], false)->toArray();
        if (empty($feeList) || empty($merchantGroupId)) return $feeList;
        $feeCodeList = $this->merchantGroupFeeConfigModel->newQuery()->where('merchant_group_id', $merchantGroupId)->pluck('fee_code')->toArray();
        if (!empty($feeCodeList)) {
            $feeList = collect($feeList)->filter(function ($fee, $key) use ($feeCodeList) {
                return !in_array($fee['code'], $feeCodeList);
            })->toArray();
        }
        return array_values($feeList);
    }


    /**
     * 配置
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function config($id, $params)
    {
        if (!empty($params['advance_days']) && !empty($params['appointment_days']) && (intval($params['advance_days']) >= intval($params['appointment_days']))) {
            throw new BusinessLogicException('可预约天数必须大于提前下单天数');
        }
        if (empty($params['appointment_days'])) {
            $params['appointment_days'] = null;
        }
        $rowCount = parent::updateById($id, Arr::only($params, ['additional_status', 'advance_days', 'appointment_days', 'delay_time', 'pickup_count', 'pie_count']));
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $this->addFeeConfigList($id, $params);
        return;
    }


    /**
     * 批量新增费用配置列表
     * @param $merchantGroupId
     * @param $params
     * @throws BusinessLogicException
     */
    private function addFeeConfigList($merchantGroupId, $params)
    {
        $rowCount = $this->merchantGroupFeeConfigModel->newQuery()->where('merchant_group_id', $merchantGroupId)->delete();
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        if (empty($params['fee_code_list'])) return;
        $feeCodeList = array_unique(explode(',', $params['fee_code_list']));
        $feeList = $this->getFeeService()->getList(['code' => ['in', $feeCodeList]], ['id', 'code'], false)->toArray();
        if (empty($feeList)) return;
        $newFeeList = [];
        foreach ($feeList as $fee) {
            $newFeeList[] = ['fee_code' => $fee['code']];
        }
        data_set($newFeeList, '*.merchant_group_id', $merchantGroupId);
        $rowCount = $this->merchantGroupFeeConfigModel->insertAll($newFeeList);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }


    /**
     * 成员信息
     * @param int $id
     * @return mixed
     */
    public function indexOfMerchant(int $id)
    {
        return $this->getMerchantService()->indexOfMerchant($id);
    }


    /**
     * 批量设置运价
     * @param $data
     * @throws BusinessLogicException
     */
    public function updatePrice($data)
    {
        $ids = json_decode($data['ids'], true);
        for ($i = 0; $i < count($ids); $i++) {
            $info = $this->update(['id' => $ids[$i]], ['transport_price_id' => $data['transport_price_id']]);
            if (empty($info)) {
                throw new BusinessLogicException('批量设置运价失败');
            }
        }
    }

    /**
     * 修改用户组所有货主状态
     * @param $id
     * @param $data
     * @return string|void
     * @throws BusinessLogicException
     */
    public function status($id, $data)
    {
        $rowCount = $this->getMerchantService()->update(['merchant_group_id' => $id], ['status' => $data['status']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }
}
