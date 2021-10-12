<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\AddressResource;
use App\Http\Validate\Api\Admin\AddressImportValidate;
use App\Http\Validate\BaseValidate;
use App\Models\Address;
use App\Models\Ledger;
use App\Services\BaseConstService;
use App\Services\CommonService;
use App\Traits\AddressTrait;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ExportTrait;
use App\Traits\ImportTrait;
use App\Traits\LocationTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LedgerService extends BaseService
{
    /**
     * @var \string[][]
     */
    public $filterRules = [
        'create_date' => ['between', ['begin_date', 'end_date']],
        'user_type' => ['=', 'user_type'],
    ];

    public $orderBy = ['id'=>'desc'];

    /**
     * AddressService constructor.
     * @param Ledger $model
     */
    public function __construct(Ledger $model)
    {
        parent::__construct($model);
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
        return $info;
    }


    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败，请重新操作');
        }
    }

    /**
     * 通过ID 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $dbData = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($dbData)) {
            throw new BusinessLogicException('数据不存在');
        } else {
            $dbData = $dbData->toArray();
        }
        $data = Arr::only($data, ['credit','status']);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        $data = array_merge($dbData, $data);
        $this->getLedgerLogService()->log($data);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPageList()
    {
        if (!empty($this->formData['user_type']) && $this->formData['user_type'] == BaseConstService::USER_MERCHANT) {
            $where = [];
            if (!empty($this->formData['code'])) {
                $where['code'] = $this->formData['code'];
            }
            if (!empty($this->formData['merchant_group_id'])) {
                $where ['merchant_group_id'] = $this->formData['merchant_group_id'];
            }
            if (!empty($where)) {
                $merchantList = $this->getMerchantService()->getList($where, ['*'], false);
                $this->query->whereIn('user_id', $merchantList->pluck('id')->toArray());
                $this->query->orderByDesc('id');
                $data = parent::getPageList();
            } else {
                $data = parent::getPageList();
                $merchantList = $this->getMerchantService()->getList(['id' => ['in', $data->pluck('user_id')->toArray()]], ['*'], false);
            }
            $merchantGroupList = $this->getMerchantGroupService()->getList(['id' => ['in', $merchantList->pluck('merchant_group_id')->toArray()]], ['*'], false);
            foreach ($data as $k => $v) {
                $merchant = $merchantList->where('id', $v['user_id'])->first();
                $data[$k]['name'] = $merchant['name'];
                $data[$k]['phone'] = $merchant['phone'];
                $data[$k]['email'] = $merchant['email'];
                $data[$k]['code'] = $merchant['code'];
                $data[$k]['merchant_group_name'] = $merchantGroupList->where('id', $merchant['merchant_group_id'])->first()['name'];
            }
        } else {
            $data = parent::getPageList();
            $merchantList = $this->getMerchantService()->getList(['id' => ['in', $data->pluck('user_id')->toArray()]], ['*'], false);
            $merchantGroupList = $this->getMerchantGroupService()->getList(['id' => ['in', $merchantList->pluck('merchant_group_id')->toArray()]], ['*'], false);
            foreach ($data as $k => $v) {
                $merchant = $merchantList->where('id', $v['user_id'])->first();
                $data[$k]['name'] = $merchant['name'];
                $data[$k]['phone'] = $merchant['phone'];
                $data[$k]['email'] = $merchant['email'];
                $data[$k]['code'] = $merchant['code'];
                $data[$k]['merchant_group_name'] = $merchantGroupList->where('id', $merchant['merchant_group_id'])->first()['name'];
            }
        }
        return $data;
    }

    public function log($id)
    {
        return $this->getLedgerLogService()->getList(['ledger_id' => $id], ['*'], false, [], ['id' => 'desc']);
    }

    /**
     * @param int $payerType
     * @param $payeeId
     * @param $expectAmount
     * @throws BusinessLogicException
     */
    public function recharge(int $payerType, $payeeId, $expectAmount)
    {
        $data = parent::getInfoLock(['user_type' => $payerType, 'user_id' => $payeeId], ['*'], false);
        if (empty($data)) {
            throw new BusinessLogicException('账户不存在');
        }
        $balance = $data['balance'] + $expectAmount;
        $row = parent::updateById($data['id'], ['balance' => $balance]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * @param int $payerType
     * @param $payeeId
     * @param $expectAmount
     * @throws BusinessLogicException
     */
    public function deduct(int $payerType, $payeeId, $expectAmount)
    {
        $data = parent::getInfoLock(['user_type' => $payerType, 'user_id' => $payeeId], ['*'], false);
        if (empty($data)) {
            throw new BusinessLogicException('账户不存在');
        }
        $balance = $data['balance'] - $expectAmount;
        if ($balance < -$data['credit']) {
            throw new BusinessLogicException('信用额度已到达上限，请及时充值');
        }
        $row = parent::updateById($data['id'], ['balance' => $balance]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
    }

}
