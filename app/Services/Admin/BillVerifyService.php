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
use App\Models\Merchant;
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
        'verify_no' => ['like', 'verify_no'],
        'pay_type' => ['=', 'pay_type']
    ];

    public $orderBy =['id'=>'desc'];


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
        $billNoList = collect($params['bill_list'])->pluck('bill_no')->toArray();
        $dbBillList = $this->getBillService()->getList(['bill_no' => ['in', $billNoList]], ['*'], false);
        if ($dbBillList->isEmpty()) {
            throw new BusinessLogicException('所选账单不存在');
        }
        if ($dbBillList->pluck('verify_no')->toArray() == [null]) {
            throw new BusinessLogicException('所选账单已生成对账单');
        }
        if($dbBillList->pluck('payer_id')->count() > 1){
            throw new BusinessLogicException('只能生成同货主的对账单');
        }
        $params['verify_no'] = $this->getOrderNoRuleService()->createBillVerifyNo();
        $params['create_date'] = today()->format('Y-m-d');
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
        if (!empty($this->formData['payer_name'])) {
            $billList = $this->getBillService()->getList(['payer_name' => ['like', $this->formData['payer_name']], 'verify_no' => ['<>', null]], ['*'], false);
            if (!empty($billList)) {
                $this->query->whereIn('verify_no', $billList->pluck('verify_no')->toArray());
            }
        }
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
            'actual_amount' => $data['actual_amount'] ?? $info['expect_amount'],
            'remark' => $data['remark'] ?? '',
            'pay_type' => $data['pay_type'],
            'picture_list' => $data['picture_list'] ?? '',
            'operator_type' => BaseConstService::USER_ADMIN,
            'operator_id' => auth()->user()->id,
            'operator_name' => auth()->user()->username,
            'status' => $data['status'],
            'verify_time' => now()
        ]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        $billList = $this->getBillService()->getList(['verify_no' => $info['verify_no']], ['*'], false);
        if (!empty($billList)) {
            foreach ($billList as $k => $v) {
                $this->getBillService()->update(['verify_no' => $info['verify_no']], [
                    'operator_type' => BaseConstService::USER_ADMIN,
                    'operator_id' => auth()->user()->id,
                    'operator_name' => auth()->user()->username,
                    'verify_status' => $data['status'],
                    'verify_time' => now(),
                    'actual_amount' => $v['expect_amount']
                ]);
            }
        }
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
            if (!empty($data['actual_amount']) && $data['actual_amount'] !== $totalAmount) {
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
        if ($info['bill_list']->isNotEmpty()) {
            $dataList = $info['bill_list']->pluck('create_date')->toArray();
            $info['begin_date'] = min($dataList);
            $info['end_date'] = max($dataList);
            $info['payer_name'] = $info['bill_list'][0]['payer_name'];
        }
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
            if ($info['status'] == BaseConstService::VERIFY_STATUS_2) {
                throw new BusinessLogicException('已对账的订单无法删除');
            }
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

    public function detailExport()
    {
        $list = self::getPageList();
    }

    /**
     * @param $merchantId
     * @throws BusinessLogicException
     */
    public function autoStore($merchantId)
    {
        $merchant = $this->getMerchantService()->getInfo(['id' => $merchantId, 'status' => BaseConstService::YES], ['*'], false);
        if ($merchant['last_settlement_date'] !== today()->format('Y-m-d')) {
            if ($merchant['settlement_type'] == BaseConstService::MERCHANT_SETTLEMENT_TYPE_2 && !empty($merchant['settlement_time'])) {
                $time = explode('-', $merchant['settlement_time']);
                $dateTime = today()->addHours($time[0])->addMinutes($time[1]);
                $billList = $this->getBillService()->getList(['verify_status' => BaseConstService::BILL_VERIFY_STATUS_1, 'created_at' => ['<', $dateTime]], ['*'], false);
            } elseif ($merchant['settlement_type'] == BaseConstService::MERCHANT_SETTLEMENT_TYPE_3 && !empty($merchant['settlement_week'])) {
                $billList = $this->getBillService()->getList(['verify_status' => BaseConstService::BILL_VERIFY_STATUS_1, 'create_date' => ['<', today()->format('Y-m-d')]], ['*'], false);
            } elseif ($merchant['settlement_type'] == BaseConstService::MERCHANT_SETTLEMENT_TYPE_4 && !empty($merchant['settlement_date'])) {
                $billList = $this->getBillService()->getList(['verify_status' => BaseConstService::BILL_VERIFY_STATUS_1, 'create_date' => ['<', today()->format('Y-m-d')]], ['*'], false);
            }
            if (!empty($billList)) {
                $this->store([
                    'bill_list' => $billList->pluck('bill_no')->toArray()
                ]);
                $this->getMerchantService()->update(['id'=>$merchantId],['last_settlement_date', today()->format('Y-m-d')]);
            }
        }
    }

}
