<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;

use App\Models\BillVerify;
use App\Models\Ledger;
use App\Services\BaseConstService;
use Illuminate\Support\Arr;


class BillVerifyService extends BaseService
{

    /**
     * @var \string[][]
     */
    public $filterRules = [
        'create_date' => ['between', ['begin_date', 'end_date']],
        'user_type' => ['=', 'user_type'],
        'verify_status' => ['=', 'verify_status'],
        'mode' => ['=', 'mode'],
        'status' => ['=', 'status'],
    ];


    /**
     * AddressService constructor.
     * @param BillVerify $model
     */
    public function __construct(BillVerify $model)
    {
        parent::__construct($model);
    }

    /**
     * 创建审核
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['verify_no'] = $this->getOrderNoRuleService()->createBillVerifyNo();
        $totalExpectAmount = 0;
        if (!empty($params['bill_list'])) {
            $billList = $this->getBillService()->getList(['bill_no' => ['in', collect($params['bill_list'])->pluck('bill_no')->toArray()]], ['*'], false);
            foreach ($billList as $k => $v) {
                $totalExpectAmount += $v['expect_amount'];
            }
        }
        $params['expect_amount'] = $totalExpectAmount;
        $bill = parent::create($params);
        if ($bill === false) {
            throw new BusinessLogicException('订单新增失败');
        }

        if (!empty($params['bill_list'])) {
            $row = $this->getBillService()->update(['bill_no' => ['in', collect($params['bill_list'])->pluck('bill_no')->toArray()]], [
                'verify_no' => $params['verify_no']
            ]);
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
        }
    }

    public function getPageList()
    {
        $data = parent::getPageList();
        foreach ($data as $k => $v) {
            $bill = $this->getBillService()->getInfo(['verify_no' => $v['verify_no']], ['*'], false);
            if (!empty($bill)) {
                $data[$k]['payer_name'] = $bill['payer_name'];
                $data[$k]['payer_id'] = $bill['payer_id'];
                $data[$k]['payer_type'] = $bill['payer_type'];
                $data[$k]['payer_type_name'] = $bill['payer_type_name'];
            }
        }
        return $data;
    }

    /**
     * 进行审核
     * @param $id
     * @param array $data
     * @throws BusinessLogicException
     */
    public function verify($id, $data)
    {
        $info = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        if ($data['status'] == BaseConstService::VERIFY_STATUS_1) {
            throw new BusinessLogicException('参数非法');
        }
//        if ($info['status'] == BaseConstService::VERIFY_STATUS_2) {
//            throw new BusinessLogicException('账单已对账，无需再次对账');
//        }
        $this->verifyCheck($data);
        $row = parent::update(['id' => $id], [
            'actual_amount' => $data['actual_amount'],
            'remark' => $data['remark'],
            'picture_list' => $data['picture_list'],
            'operator_type' => BaseConstService::USER_ADMIN,
            'operator_id' => auth()->user()->id,
            'operator_name' => auth()->user()->username,
            'status' => $data['status'],
            'verify_time' => now()
        ]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        $this->getBillService()->update(['verify_no' => $info['verify_no']], [
            'operator_type' => BaseConstService::USER_ADMIN,
            'operator_id' => auth()->user()->id,
            'operator_name' => auth()->user()->username,
            'verify_status' => $data['status'],
            'verify_time' => now(),
        ]);
    }

    /**
     * 检查参数内部各实际金额是否等于总金额
     * @param $data
     * @throws BusinessLogicException
     */
    public function verifyCheck($data)
    {
        if (!empty($data['bill_list'])) {
            $totalAmount = 0;
            foreach ($data['bill_list'] as $k => $v) {
                $totalAmount += $v['actual_amount'] ?? 0;
            }
            if ($data['actual_amount'] !== $totalAmount) {
                throw new BusinessLogicException('合计错误');
            }
        }
    }

    /**
     * 详情
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
        $info['bill_list'] = $this->getBillService()->getList(['verify_no' => $info['verify_no']], ['*'], false);
        $info['begin_date'] = min($info['bill_list']->pluck('create_date')->toArray());
        $info['end_date'] = max($info['bill_list']->pluck('create_date')->toArray());
        $info['payer_name'] = $info['bill_list'][0]['payer_name'];
        return $info;
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $info = parent::getInfoLock(['id' => $id], ['*'], false);
        if (!empty($info)) {
            $row = parent::delete(['id' => $id]);
            if ($row == false) {
                throw new BusinessLogicException('删除失败');
            }
            $this->getBillService()->update(['verify_no' => $info['verify_no']], [
                'operator_type' => null,
                'operator_id' => null,
                'operator_name' => null,
                'status' => BaseConstService::VERIFY_STATUS_1,
            ]);

        }

    }

}
