<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;

use App\Models\Bill;
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
        'mode' => ['=', 'mode']
    ];


    /**
     * AddressService constructor.
     * @param Bill $model
     */
    public function __construct(Bill $model)
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
        $bill = parent::create($params);
        if ($bill === false) {
            throw new BusinessLogicException('订单新增失败');
        }
    }

    public function getPageList()
    {
        return parent::getPageList();
    }

    /**
     * 进行审核
     * @param $id
     * @param array $data
     */
    public function verify($id, $data)
    {

    }

}
