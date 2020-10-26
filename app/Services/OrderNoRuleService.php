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
            throw new BusinessLogicException('操作失败');
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
            throw new BusinessLogicException('操作失败');
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
            throw new BusinessLogicException('操作失败');
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
            throw new BusinessLogicException("单号规则总长度不得超过:length位", 1000, ['length' => BaseConstService::ORDER_NO_RULE_LENGTH]);
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
            throw new BusinessLogicException('操作失败');
        }
    }


    /**
     * 创建订单编号
     * @param $type
     * @return string
     * @throws BusinessLogicException
     */
    public function createOrderNo()
    {
        $info = parent::getInfoLock(['company_id' => auth()->user()->company_id, 'type' => BaseConstService::ORDER_NO_TYPE, 'status' => BaseConstService::ON], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('订单单号规则不存在或已被禁用，请先联系后台管理员');
        }
        $info = $info->toArray();
        $orderNo = $info['prefix'] . $info['start_string_index'] . sprintf("%0{$info['int_length']}s", $info['start_index']);
        //修改索引
        $startStringIndex = !empty($info['start_string_index']) ? AlphaTrait::getNextString($info['start_string_index']) : '';
        $index = ($startStringIndex === str_repeat('A', $info['string_length'])) ? $info['start_index'] + 1 : $info['start_index'];
        $rowCount = parent::updateById($info['id'], ['start_index' => $index, 'start_string_index' => $startStringIndex]);
        if ($rowCount === false) {
            throw new BusinessLogicException('单号生成失败，请重新操作');
        }
        return $orderNo;
    }

    /**
     * 创建取派批次编号
     * @return string
     * @throws BusinessLogicException
     */
    public function createBatchNo()
    {
        $info = parent::getInfoLock(['company_id' => auth()->user()->company_id, 'type' => BaseConstService::BATCH_NO_TYPE, 'status' => BaseConstService::ON], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('站点单号规则不存在或已被禁用，请先联系后台管理员');
        }
        $info = $info->toArray();
        $orderNo = $info['prefix'] . $info['start_string_index'] . sprintf("%0{$info['int_length']}s", $info['start_index']);
        //修改索引
        $startStringIndex = !empty($info['start_string_index']) ? AlphaTrait::getNextString($info['start_string_index']) : '';
        $index = ($startStringIndex === str_repeat('A', $info['string_length'])) ? $info['start_index'] + 1 : $info['start_index'];
        $rowCount = parent::updateById($info['id'], ['start_index' => $index, 'start_string_index' => $startStringIndex]);
        if ($rowCount === false) {
            throw new BusinessLogicException('单号生成失败，请重新操作');
        }
        if (empty($info)) {
            throw new BusinessLogicException('单号规则不存在，请先添加单号规则');
        }
        return $orderNo;
    }


    /**
     * 创建取件线路编号
     * @return string
     * @throws BusinessLogicException
     */
    public function createTourNo()
    {
        $info = parent::getInfoLock(['company_id' => auth()->user()->company_id, 'type' => BaseConstService::TOUR_NO_TYPE, 'status' => BaseConstService::ON], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('取件线路单号规则不存在或已被禁用，请先联系后台管理员');
        }
        $info = $info->toArray();
        $orderNo = $info['prefix'] . $info['start_string_index'] . sprintf("%0{$info['int_length']}s", $info['start_index']);
        //修改索引
        $startStringIndex = !empty($info['start_string_index']) ? AlphaTrait::getNextString($info['start_string_index']) : '';
        $index = ($startStringIndex === str_repeat('A', $info['string_length'])) ? $info['start_index'] + 1 : $info['start_index'];
        $rowCount = parent::updateById($info['id'], ['start_index' => $index, 'start_string_index' => $startStringIndex]);
        if ($rowCount === false) {
            throw new BusinessLogicException('单号生成失败，请重新操作');
        }
        return $orderNo;
    }

    /**
     * 创建站点异常编号
     * @return string
     * @throws BusinessLogicException
     */
    public function createBatchExceptionNo()
    {
        $info = parent::getInfoLock(['company_id' => auth()->user()->company_id, 'type' => BaseConstService::BATCH_EXCEPTION_NO_TYPE, 'status' => BaseConstService::ON], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('异常站点单号规则不存在或已被禁用，请先联系后台管理员');
        }
        $info = $info->toArray();
        $orderNo = $info['prefix'] . $info['start_string_index'] . sprintf("%0{$info['int_length']}s", $info['start_index']);
        //修改索引
        $startStringIndex = !empty($info['start_string_index']) ? AlphaTrait::getNextString($info['start_string_index']) : '';
        $index = ($startStringIndex === str_repeat('A', $info['string_length'])) ? $info['start_index'] + 1 : $info['start_index'];
        $rowCount = parent::updateById($info['id'], ['start_index' => $index, 'start_string_index' => $startStringIndex]);
        if ($rowCount === false) {
            throw new BusinessLogicException('单号生成失败，请重新操作');
        }
        return $orderNo;
    }

    /**
     * 创建充值单号
     * @return int
     * @throws BusinessLogicException
     */
    public function createRechargeNo()
    {
        $info = parent::getInfoLock(['company_id' => auth()->user()->company_id, 'type' => BaseConstService::RECHARGE_NO_TYPE, 'status' => BaseConstService::ON], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('充值单号规则不存在或已被禁用，请先联系后台管理员');
        }
        $info = $info->toArray();
        $orderNo = $info['prefix'] . $info['start_string_index'] . sprintf("%0{$info['int_length']}s", $info['start_index']);
        //修改索引
        $startStringIndex = !empty($info['start_string_index']) ? AlphaTrait::getNextString($info['start_string_index']) : '';
        $index = ($startStringIndex === str_repeat('A', $info['string_length'])) ? $info['start_index'] + 1 : $info['start_index'];
        $rowCount = parent::updateById($info['id'], ['start_index' => $index, 'start_string_index' => $startStringIndex]);
        if ($rowCount === false) {
            throw new BusinessLogicException('单号生成失败，请重新操作');
        }
        return $orderNo;
    }

}
