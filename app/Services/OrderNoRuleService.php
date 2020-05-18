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
use App\Http\Resources\OrderNoRuleResource;
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
        if (!array_key_exists($params['type'], ConstTranslateTrait::$noTypeList)) {
            throw new BusinessLogicException('当前编号规则未定义');
        }
        $dbNoRule = parent::getInfo(['type' => $params['type']], ['id'], false);
        if (!empty($dbNoRule)) {
            throw new BusinessLogicException('当前单号规则已存在');
        }
        //若字母长度不为0,则生成开始字符索引
        $params['start_string_index'] = str_repeat('A', $params['string_length']);
        $params['max_no'] = $params['prefix'] . str_repeat('Z', $params['string_length']) . str_repeat('9', $params['length']);
        $this->check($params);
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
        $data = Arr::only($data, ['type', 'prefix', 'length', 'string_length']);
        $this->check($data, $id);
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if ($info['string_length'] != $data['string_length']) {
            $data['start_string_index'] = str_repeat('A', $data['string_length']);
        }
        $data['max_no'] = $data['prefix'] . str_repeat('Z', $data['string_length']) . str_repeat('9', $data['length']);
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
    private function check($params, $id = null)
    {
        $query = DB::table('order_no_rule');
        !empty($id) && $query->where('id', '<>', $id);
        //判断前缀是否存在
        $dbNoRule = $query->where('prefix', $params['prefix'])->first();
        if (!empty($dbNoRule)) {
            throw new BusinessLogicException('系统的开始字符已存在');
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
            throw new BusinessLogicException('订单单号规则不存在或已被金庸，请先联系后台管理员');
        }
        $info = $info->toArray();
        $orderNo = $info['prefix'] . $info['start_string_index'] . sprintf("%0{$info['length']}s", $info['start_index']);
        //修改索引
        $startStringIndex = !empty($info['start_string_index']) ? AlphaTrait::getNextString($info['start_string_index']) : '';
        $index = ($startStringIndex === str_repeat('A', $info['string_length'])) ? $info['start_index'] + 1 : $info['start_index'];
        $rowCount = parent::updateById($info['id'], ['start_index' => $index, 'start_string_index' => $startStringIndex]);
        if ($rowCount === false) {
            throw new BusinessLogicException('单号生成失败，请重新操作');
        }
        return $orderNo;
    }

    public function createNo($type)
    {
        $info = parent::getInfoLock(['company_id' => auth()->user()->company_id, 'type' => $type], ['*'], false)->toArray();
        if (empty($info)) {
            throw new BusinessLogicException('单号规则不存在，请先添加单号规则');
        }
        $letterPart = '';
        $letter = '';
        $number = '';
        if ($info['letterLength'] > 0) {
            $number = substr((string)$info['start_index'], -$info['numberLength']);
            $letterPart = str_replace($number, '', (string)$info['start_index']);
        } else {
            $letterPart = 0;
        }
        $letterPart = str_pad(base_convert((int)$letterPart, 10, 25), $info['letterLength'], "0", STR_PAD_LEFT);
        $arr = ['0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', 'A' => 'K', 'B' => 'L', 'C' => 'M', 'D' => 'N', 'E' => 'O', 'F' => 'P', 'G' => 'Q', 'H' => 'R', 'I' => 'S', 'J' => 'T', 'K' => 'U', 'L' => 'V', 'M' => 'W', 'N' => 'X', 'O' => 'Y', 'P' => 'Z'];
        for ($i = 0, $j = strlen($letterPart); $i < $j; $i++) {//遍历字符串追加给数组
            $letterPart[$i] = substr($letterPart[$i], $i);
            $letter = $letter . $arr[$letterPart[$i]];
        }
        $no = $info['prefix'] . $letter . $number;
        $rowCount = parent::updateById($info['id'], ['start_index' => $info['start_index'] + 1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('单号生成失败，请重新操作');
        }
        return $no;
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
        $orderNo = $info['prefix'] . $info['start_string_index'] . sprintf("%0{$info['length']}s", $info['start_index']);
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
        $orderNo = $info['prefix'] . $info['start_string_index'] . sprintf("%0{$info['length']}s", $info['start_index']);
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
        $orderNo = $info['prefix'] . $info['start_string_index'] . sprintf("%0{$info['length']}s", $info['start_index']);
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
