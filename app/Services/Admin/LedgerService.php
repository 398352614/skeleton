<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Admin;

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
        $data = Arr::only($data, 'credit');
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPageList()
    {
        if ($this->formData['user_type'] == BaseConstService::USER_MERCHANT) {
            if (!empty($this->formData['code'])) {
                $where = ['code' => $this->formData['code']];
            }
            if (!empty($this->formData['merchant_group_id'])) {
                $where = ['merchant_group_id' => $this->formData['merchant_group_id']];
            }
            if (!empty($where)) {
                $merchantList = $this->getMerchantService()->getList($where, ['*'], false);
                $this->query->whereIn('user_id', $merchantList->pluck('id')->toArray());
                $this->query->orderByDesc('id');
                $data = parent::getPageList();
            } else {
                $data = parent::getPageList();
                $merchantList = $this->getMerchantService()->getList('id', $data->pluck('user_id')->toArray());
            }
            $merchantGroupList = $this->getMerchantGroupService()->getList(['id' => ['in', $merchantList->pluck('merchant_group_id')->toArray()]], ['*'], false);
            foreach ($data as $k => $v) {
                $merchant = Arr::only($merchantList->where('id', $v['user_id'])->first(), ['code', 'merchant_group_id']);
                $data[$k] = array_merge($v, $merchant['code']);
                $data[$k]['merchant_group_name'] = $merchantGroupList->where('id', $merchant['merchant_group_id'])->first()['name'];
            }
            return $data;
        }
    }

}
