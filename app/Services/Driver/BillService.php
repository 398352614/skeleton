<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Models\Bill;
use App\Services\BaseConstService;
use App\Traits\UserTrait;
use Illuminate\Support\Arr;


class BillService extends BaseService
{

    /**
     * @var \string[][]
     */
    public $filterRules = [
        'create_date' => ['between', ['begin_date', 'end_date']],
        'user_type' => ['=', 'user_type'],
        'verify_status' => ['=', 'verify_status'],
        'mode' => ['=', 'mode'],
        'object_no' => ['like', 'object_no'],
        'bill_no' => ['like', 'bill_no'],
        'verify_no' => ['like', 'verify_no'],
        'status' => ['=', 'status']
    ];


    /**
     * AddressService constructor.
     * @param Bill $model
     */
    public function __construct(Bill $model)
    {
        parent::__construct($model);
    }

    public $orderBy = ['id' => 'desc'];

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['bill_no'] = $this->getOrderNoRuleService()->createBillNo();
        $params['create_date'] = today()->format('Y-m-d');
        $bill = parent::create($params);
        if ($bill === false) {
            throw new BusinessLogicException('订单新增失败');
        }
    }

    /**
     * 查询
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPageList()
    {
        if ((!empty($this->formData['user_type']) && $this->formData['user_type'] == BaseConstService::USER_MERCHANT) || empty($this->formData['user_type'])) {
            $where = [];
            if (!empty($this->formData['code'])) {
                $where['code'] = $this->formData['code'];
            }
            if (!empty($this->formData['merchant_group_id'])) {
                $where ['merchant_group_id'] = $this->formData['merchant_group_id'];
            }
            if (!empty($where)) {
                $merchantList = $this->getMerchantService()->getList($where, ['*'], false);
                $this->query->whereIn('payer_id', $merchantList->pluck('id')->toArray());
                $this->query->orderByDesc('id');
                $data = parent::getPageList();

            } else {
                $data = parent::getPageList();
                $merchantList = $this->getMerchantService()->getList(['id' => ['in', $data->pluck('payer_id')->toArray()]], ['*'], false);
            }
            $merchantGroupList = $this->getMerchantGroupService()->getList(['id' => ['in', $merchantList->pluck('merchant_group_id')->toArray()]], ['*'], false);
            foreach ($data as $k => $v) {
                $merchant = $merchantList->where('id', $v['payer_id'])->first();
                if (!empty($merchant)) {
                    $data[$k]['code'] = $merchant['code'];
                    $data[$k]['merchant_group_name'] = $merchantGroupList->where('id', $merchant['merchant_group_id'])->first()['name'];
                }
            }
        } else {
            $data = parent::getPageList();
        }
        return $data;
    }

    /**
     * @param array $data
     * @throws BusinessLogicException
     */
    public function merchantRecharge(array $data)
    {
        $data['mode'] = BaseConstService::BILL_MODE_1;
        $data['create_Date'] = today()->format('Y-m-d');
        $data['actual_amount'] = 0;
        $data['payer_type'] = BaseConstService::USER_MERCHANT;
        $data['payer_id'] = $data['merchant_id'];
        $data['payer_name'] = UserTrait::get($data['payer_id'], BaseConstService::USER_MERCHANT)['name'];
        $data['payee_id'] = auth()->user()->company_id;
        $data['payee_type'] = BaseConstService::USER_COMPANY;
        $data['payee_name'] = $this->getCompanyService()->getInfo(['id' => auth()->user()->id], ['*'], false)->toArray()['name'] ?? '';
        $data['operator_id'] = auth()->user()->id;
        $data['operator_type'] = BaseConstService::USER_ADMIN;
        $data['operator_name'] = auth()->user()->username;
        $data['create_timing'] = BaseConstService::BILL_CREATE_TIMING_2;
        $data['pay_timing'] = BaseConstService::BILL_PAY_TIMING_1;
        $data['status'] = BaseConstService::BILL_STATUS_2;
        self::store($data);
    }


    /**
     * @param $data
     * @param $user
     * @throws BusinessLogicException
     * pay_type,actual_amount,status
     */
    public function pay($data, $user)
    {
        $dbData = parent::getInfoLock(['bill_no' => $data['bill_no']], ['*'], false);
        if (empty($dbData)) {
            throw new BusinessLogicException('数据不存在');
        }
        if ($dbData['status'] !== BaseConstService::BILL_STATUS_1) {
            throw new BusinessLogicException('状态错误');
        }
        if ($data['status'] == BaseConstService::BILL_STATUS_1) {
            throw new BusinessLogicException('非法参数');
        }
        $row = parent::update(['id' => $dbData['id']], [
            'actual_amount' => $data['actual_amount'] ?? 0,
            'status' => $data['status'],
            'operator_type' => $user['user_type'],
            'operator_id' => $user['id'],
            'operator_name' => $user['name'],
            'pay_type' => $data['pay_type']
        ]);
        if ($row == false) {
            throw new BusinessLogicException('支付失败');
        }
    }

    /**
     * @param array $data
     * @throws BusinessLogicException
     */
    public function merchantDeduct(array $data)
    {
        $data['mode'] = BaseConstService::BILL_MODE_1;
        $data['create_Date'] = today()->format('Y-m-d');
        $data['actual_amount'] = 0;
        $data['payer_type'] = BaseConstService::USER_MERCHANT;
        $data['payee_id'] = auth()->user()->company_id;
        $data['payee_type'] = BaseConstService::USER_COMPANY;
        $data['operator_id'] = auth()->user()->id;
        $data['operator_type'] = BaseConstService::USER_ADMIN;
        $data['operator_name'] = auth()->user()->username;
        $data['crate_timing'] = BaseConstService::BILL_CREATE_TIMING_2;
        $data['pay_timing'] = BaseConstService::BILL_PAY_TIMING_1;
        $this->store($data);
    }

    /**
     * @param $id
     * @param $data
     * @return int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $dbData = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($dbData)) {
            throw new BusinessLogicException('数据不存在');
        }
        $data = Arr::only($data, 'actual_amount');
        $row = parent::update(['id' => $id], $data);
        if ($row == false) {
            throw new BusinessLogicException('修改失败');
        }
    }


    public function show($id)
    {
        $data = parent::getInfo(['id' => $id], ['*'], false);
        if ($data['payer_type'] == BaseConstService::USER_MERCHANT) {
            $merchant = $this->getMerchantService()->getInfo(['id' => $data['payer_id']], ['*'], false);
            if (!empty($merchant)) {
                $merchantGroup = $this->getMerchantGroupService()->getInfo(['id' => $merchant['merchant_group_id']], ['*'], false);
            }
            $data['code'] = $merchant['code'];
            $data['merchant_group_name'] = $merchantGroup['name'] ?? '';
        }
        return $data;
    }

    /**
     * 通过主体获取账单
     * @param $info
     * @return array|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getByObject($info)
    {
        $data = [];
        if (key_exists('package_no', $info)) {
            $data = parent::getList(['object_no' => $info['package_no'], 'type' => BaseConstService::BILL_OBJECT_TYPE_2], ['*'], false);
        } elseif (key_exists('order_no', $info)) {
            $packageList = $this->getTrackingOrderPackageService()->getList(['order_no' => $info['order_no']], ['*'], false);
            $data = array_merge(
                parent::getList([
                    'object_no' => $info['order_no'],
                    'status' => ['in', [BaseConstService::BILL_STATUS_1, BaseConstService::BILL_STATUS_2]],
                    'type' => BaseConstService::BILL_OBJECT_TYPE_2
                ], ['*'], false),
                parent::getList([
                    'object_no' => ['in', $packageList->pluck('first_press_no')->toArray()],
                    'status' => ['in', [BaseConstService::BILL_STATUS_1, BaseConstService::BILL_STATUS_2]],
                    'type' => BaseConstService::BILL_OBJECT_TYPE_2
                ], ['*'], false)
            );
        } elseif (key_exists('batch_no', $info)) {
            $packageList = $this->getTrackingOrderPackageService()->getList(['batch_no' => $info['batch_no']], ['*'], false);
            $trackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $info['batch_no']], ['*'], false);
            $data = array_merge(
                parent::getList([
                    'object_no' => $info['batch_no'],
                    'status' => ['in', [BaseConstService::BILL_STATUS_1, BaseConstService::BILL_STATUS_2]],
                    'type' => BaseConstService::BILL_OBJECT_TYPE_2
                ], ['*'], false)->toArray() ?? [],
                parent::getList([
                    'object_no' => ['in', $packageList->pluck('express_first_no')->toArray()],
                    'status' => ['in', [BaseConstService::BILL_STATUS_1, BaseConstService::BILL_STATUS_2]],
                    'type' => BaseConstService::BILL_OBJECT_TYPE_2
                ], ['*'], false)->toArray() ?? [],
                parent::getList(['object_no' => ['in', $trackingOrderList->pluck('order_no')->toArray()],
                    'status' => ['in', [BaseConstService::BILL_STATUS_1, BaseConstService::BILL_STATUS_2]],
                    'type' => BaseConstService::BILL_OBJECT_TYPE_2
                ], ['*'], false)->toArray() ?? []
            );
        }
        return $data;
    }

    /**
     * @param $info
     * @return array
     */
    public function getListByBatch($info)
    {
        $packageList = $this->getTrackingOrderPackageService()->getList(['batch_no' => $info['batch_no']], ['*'], false);
        $trackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $info['batch_no']], ['*'], false);
        $data = array_merge(
            //站点费用
            parent::getList([
                'object_no' => $info['batch_no'],
                'status' => ['in', [BaseConstService::BILL_STATUS_1, BaseConstService::BILL_STATUS_2]],
                'type' => BaseConstService::BILL_TYPE_2
            ], ['*'], false)->toArray() ?? [],
            //包裹费用
            parent::getList([
                'object_no' => ['in', $packageList->pluck('express_first_no')->toArray()],
                'status' => ['in', [BaseConstService::BILL_STATUS_1, BaseConstService::BILL_STATUS_2]],
                'type' => BaseConstService::BILL_TYPE_2
            ], ['*'], false)->toArray() ?? [],
            //取件订单费用
            parent::getList(['object_no' => ['in', $trackingOrderList->where('type', BaseConstService::TRACKING_ORDER_TYPE_1)->pluck('order_no')->toArray()],
                'status' => ['in', [BaseConstService::BILL_STATUS_1, BaseConstService::BILL_STATUS_2]],
                'pay_timing' => BaseConstService::BILL_PAY_TIMING_2
            ], ['*'], false)->toArray() ?? [],
            //派件订单费用
            parent::getList(['object_no' => ['in', $trackingOrderList->where('type', BaseConstService::TRACKING_ORDER_TYPE_2)->pluck('order_no')->toArray()],
                'status' => ['in', [BaseConstService::BILL_STATUS_1, BaseConstService::BILL_STATUS_2]],
                'pay_timing' => BaseConstService::BILL_PAY_TIMING_3
            ], ['*'], false)->toArray() ?? []
        );
        $data = collect($data)->groupBy('fee_id')->toArray();
        $newData = [];
        $newData[0]['fee_id'] = 0;
        $newData[0]['expect_amount'] = 0;
        $newData[0]['actual_amount'] = 0;
        foreach ($data as $k => $v) {
            $newData[$k]['fee_id'] = $k;
            $newData[$k]['expect_amount'] = 0;
            $newData[$k]['actual_amount'] = 0;
            foreach ($v as $x => $y) {
                $newData[$k]['expect_amount'] += $y['expect_amount'];
                $newData[$k]['actual_amount'] += $y['actual_amount'];
                $newData[$k]['fee_name'] = $y['fee_name'];
            }
            $newData[0]['expect_amount'] += $newData[$k]['expect_amount'];
            $newData[0]['actual_amount'] += $newData[$k]['actual_amount'];
            $newData[$k]['expect_amount'] = number_format($newData[$k]['expect_amount'], 2);
            $newData[$k]['actual_amount'] = number_format($newData[$k]['actual_amount'], 2);
        }
        $newData[0]['expect_amount'] = number_format($newData[0]['expect_amount'], 2);
        $newData[0]['actual_amount'] = number_format($newData[0]['actual_amount'], 2);
        $newData[0]['fee_name'] = __('总计');
        asort($newData);
        $newData = array_values($newData);
        return $newData;
    }
}
