<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Models\BillVerify;
use App\Services\BaseConstService;


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

    public $orderBy = ['id' => 'desc'];


    /**
     * AddressService constructor.
     * @param BillVerify $model
     */
    public function __construct(BillVerify $model)
    {
        parent::__construct($model);
    }


    public function getPageList()
    {
        $billList = $this->getBillService()->getList(['payer_id' => auth()->user()->id, 'verify_no' => ['<>', null]], ['*'], false);
        if (!empty($billList)) {
            $this->query->whereIn('verify_no', $billList->pluck('verify_no')->toArray());
            $data = parent::getPageList();
            foreach ($data as $k => $v) {
                $bill = $billList->where('verify_no', $v['verify_no'])->first();
                $data[$k]['object_no'] = $bill['object_no'];
                $data[$k]['create_date'] = $bill['create_date'];
                $data[$k]['created_at'] = $bill['created_at'];
            }
            return $data;
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


}
