<?php
/**
 * 单号规则 服务
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/18
 * Time: 14:01
 */

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\OrderNoRuleResource;
use App\Models\OrderNoRule;
use App\Traits\AlphaTrait;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderNoRuleService extends BaseService
{
    public function __construct(OrderNoRule $orderNoRule)
    {
        parent::__construct($orderNoRule, OrderNoRuleResource::class);
    }

    public function getPageList()
    {
        $this->query->orderBy('id');
        return parent::getPageList();
    }

    /**
     * 新增初始化
     * @return array
     */
    public function initStore()
    {
        $data = [];
        $dbTypeList = parent::getList([], ['type'], false)->toArray();
        $dbTypeList = !empty($dbTypeList) ? array_flip(array_column($dbTypeList, 'type')) : [];
        $data['type_list'] = ConstTranslateTrait::formatList(array_diff_key(ConstTranslateTrait::$noTypeList, $dbTypeList));
        return $data;
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->check($params);
        $params = Arr::only($params, ['type', 'prefix', 'int_length', 'string_length', 'status']);
        if (!array_key_exists($params['type'], ConstTranslateTrait::$noTypeList)) {
            throw new BusinessLogicException('当前编号规则未定义');
        }
        //若字母长度不为0,则生成开始字符索引
        $params['start_string_index'] = str_repeat('A', $params['string_length']);
        $params['max_no'] = $params['prefix'] . str_repeat('Z', $params['string_length']) . str_repeat('9', $params['int_length']);
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
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
        $data = Arr::only($data, ['prefix', 'int_length', 'string_length', 'status', 'type']);
        $this->check($data, $id);
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $info = $info->toArray();
        $data['max_no'] = $data['prefix'] . str_repeat('Z', $data['string_length']) . str_repeat('9', $data['int_length']);
        if ($info['max_no'] != $data['max_no']) {
            $data['start_string_index'] = str_repeat('A', $data['string_length']);
            $data['start_index'] = 1;
        }
        $data = Arr::only($data, ['prefix', 'int_length', 'string_length', 'start_string_index', 'start_index', 'max_no', 'status']);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }

    /**
     * 验证
     * @param $params
     * @param null $id
     * @throws BusinessLogicException
     */
    private function check(&$params, $id = null)
    {
        $params['string_length'] = empty($params['string_length']) ? 0 : $params['string_length'];
        if (strlen($params['prefix']) + $params['int_length'] + $params['string_length'] > BaseConstService::ORDER_NO_RULE_LENGTH) {
            throw new BusinessLogicException("单号规则总长度不得超过[:length]位", 1000, ['length' => BaseConstService::ORDER_NO_RULE_LENGTH]);
        }
        $info = DB::table('order_no_rule')->where('type', $params['type'])->where('prefix', $params['prefix'])->first();
        if (!empty($info) && $info->id != $id) {
            throw new BusinessLogicException('前缀与其他用户重复，请更改前缀');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }


    /**
     * 创建订单编号
     * @return string
     * @throws BusinessLogicException
     */
    public function createOrderNo()
    {
        return $this->createNoBase(BaseConstService::ORDER_NO_TYPE, '订单单号规则不存在或已被禁用，请先联系后台管理员');
    }

    /**
     * 创建取派批次编号
     * @return string
     * @throws BusinessLogicException
     */
    public function createBatchNo()
    {
        return $this->createNoBase(BaseConstService::BATCH_NO_TYPE, '站点单号规则不存在或已被禁用，请先联系后台管理员');
    }


    /**
     * 创建线路任务编号
     * @return string
     * @throws BusinessLogicException
     */
    public function createTourNo()
    {
        return $this->createNoBase(BaseConstService::TOUR_NO_TYPE, '线路任务单号规则不存在或已被禁用，请先联系后台管理员');
    }

    /**
     * 创建站点异常编号
     * @return string
     * @throws BusinessLogicException
     */
    public function createBatchExceptionNo()
    {
        return $this->createNoBase(BaseConstService::BATCH_EXCEPTION_NO_TYPE, '异常站点单号规则不存在或已被禁用，请先联系后台管理员');
    }

    /**
     * 创建充值单号
     * @return int
     * @throws BusinessLogicException
     */
    public function createRechargeNo()
    {
        return $this->createNoBase(BaseConstService::RECHARGE_NO_TYPE, '充值单号规则不存在或已被禁用，请先联系后台管理员');
    }


    /**
     * 创建运单编号
     * @return string
     * @throws BusinessLogicException
     */
    public function createTrackingOrderNo()
    {
        return $this->createNoBase(BaseConstService::TRACKING_ORDER_NO_TYPE, '运单单号规则不存在或已被禁用，请先联系后台管理员');
    }

    /**
     * 创建入库异常编号
     * @return string
     * @throws BusinessLogicException
     */
    public function createStockExceptionNo()
    {
        return $this->createNoBase(BaseConstService::STOCK_EXCEPTION_NO_TYPE, '入库异常单号规则不存在或已被禁用，请先联系后台管理员');
    }

    /**
     * 创建事故处理单号
     * @throws BusinessLogicException
     */
    public function createCarAccidentNo()
    {
        return $this->createNoBase(BaseConstService::CAR_ACCIDENT_NO_TYPE, '事故处理编号规则不存在或已被禁用，请先联系后台管理员');
    }

    /**
     * 创建车辆维护流水号
     * @return string
     * @throws BusinessLogicException
     */
    public function createCarMaintainNo()
    {
        return $this->createNoBase(BaseConstService::CAR_MAINTAIN_NO_TYPE, '车辆维护流水号规则不存在或已被禁用，请先联系后台管理员');
    }

    /**
     * 创建转运单号
     * @return string
     * @throws BusinessLogicException
     */
    public function createTrackingPackageNo()
    {
        return $this->createNoBase(BaseConstService::TRACKING_PACKAGE_NO_TYPE, '转运单号规则不存在或已被禁用，请先联系后台管理员');
    }

    /**
     * 创建袋号
     * @return string
     * @throws BusinessLogicException
     */
    public function createBagNo()
    {
        return $this->createNoBase(BaseConstService::BAG_NO_TYPE, '袋号规则不存在或已被禁用，请先联系后台管理员');
    }

    /**
     * 创建车次号
     * @return string
     * @throws BusinessLogicException
     */
    public function createShiftNo()
    {
        return $this->createNoBase(BaseConstService::SHIFT_NO_TYPE, '车次号规则不存在或已被禁用，请先联系后台管理员');
    }


    /**
     * 创建各种规则的编号
     * @param  string  $ruleType
     * @param  string  $infoFailMsg
     * @param  string  $updateFailMsg
     * @return string
     * @throws BusinessLogicException
     */
    protected function createNoBase(string $ruleType, string $infoFailMsg, string $updateFailMsg = '单号生成失败，请重新操作')
    {
        $info = parent::getInfoLock(['company_id' => auth()->user()->company_id, 'type' => $ruleType, 'status' => BaseConstService::ON], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException($infoFailMsg);
        }
        $info = $info->toArray();
        $orderNo = $info['prefix'] . $info['start_string_index'] . sprintf("%0{$info['int_length']}s", $info['start_index']);
        //修改索引
        $startStringIndex = !empty($info['start_string_index']) ? AlphaTrait::getNextString($info['start_string_index']) : '';
        $index = ($startStringIndex === str_repeat('A', $info['string_length'])) ? $info['start_index'] + 1 : $info['start_index'];
        $rowCount = parent::updateById($info['id'], ['start_index' => $index, 'start_string_index' => $startStringIndex]);
        if ($rowCount === false) {
            throw new BusinessLogicException($updateFailMsg);
        }
        return $orderNo;
    }

//    public function baseCreateNo($noRuleId,$type)
//    {
//        $lock=$this->getNoRuleLock($noRuleId);
//        if($lock == BaseConstService::YES){
//            $tryTime=BaseConstService::TRY_TIME;
//            sleep(0.1);
//        }
//        $info = parent::getInfo(['company_id' => auth()->user()->company_id, 'type' => $type, 'status' => BaseConstService::ON], ['*'], false);
//        if (empty($info)) {
//            throw new BusinessLogicException('编号规则不存在或已被禁用，请先联系后台管理员');
//        }
//        $info = $info->toArray();
//        $orderNo = $info['prefix'] . $info['start_string_index'] . sprintf("%0{$info['int_length']}s", $info['start_index']);
//        //修改索引
//        $startStringIndex = !empty($info['start_string_index']) ? AlphaTrait::getNextString($info['start_string_index']) : '';
//        $index = ($startStringIndex === str_repeat('A', $info['string_length'])) ? $info['start_index'] + 1 : $info['start_index'];
//        $rowCount = parent::updateById($info['id'], ['start_index' => $index, 'start_string_index' => $startStringIndex]);
//        if ($rowCount === false) {
//            throw new BusinessLogicException('单号生成失败，请重新操作');
//        }
//        return $orderNo;
//    }

}
