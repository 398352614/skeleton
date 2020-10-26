<?php
/**
 * 商户列表 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\MerchantResource;
use App\Models\Merchant;
use App\Models\MerchantFeeConfig;
use App\Models\MerchantGroup;
use App\Services\BaseConstService;
use App\Services\Admin\BaseService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ExportTrait;
use Illuminate\Hashing\Argon2IdHasher;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\False_;
use Vinkla\Hashids\Facades\Hashids;

/**
 * Class MerchantService
 * @package App\Services\Admin
 * @property MerchantFeeConfig $merchantFeeConfigModel
 */
class MerchantService extends BaseService
{
    use ExportTrait;

    protected $merchantFeeConfigModel;

    public $filterRules = [
        'name' => ['like', 'name'],
        'merchant_group_id' => ['=', 'merchant_group_id'],
        'status' => ['=', 'status']
    ];

    protected $headings = [
        'type',
        'name',
        'email',
        'country',
        'settlement_type',
        'merchant_group_id',
        'contacter',
        'phone',
        'address',
        'status'
    ];

    public function __construct(Merchant $merchant)
    {
        parent::__construct($merchant, MerchantResource::class);
        $this->merchantFeeConfigModel = new MerchantFeeConfig();
    }

    public function init()
    {
        $data = [];
        $data['settlement_type_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$merchantSettlementTypeList);
        return $data;
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
        $info = $info->toArray();
        $info['merchant_group_name'] = MerchantGroup::query()->where('id', $info['merchant_group_id'])->value('name');
        unset($info['password']);
        //获取费用列表
        $feeCodeList = $this->merchantFeeConfigModel->newQuery()->where('merchant_id', $info['id'])->pluck('fee_code')->toArray();
        if (empty($feeCodeList)) {
            $info['fee_list'] = [];
        } else {
            $feeList = $this->getFeeService()->getList(['code' => ['in', $feeCodeList]], ['id', 'code', 'name'], false)->toArray();
            $info['fee_list'] = $feeList;
        }
        return $info;
    }

    /**
     * 获取费用列表
     * @param null $merchantId
     * @return array
     */
    public function getFeeList($merchantId = null)
    {
        $feeList = $this->getFeeService()->getList([], ['id', 'code', 'name'], false)->toArray();
        if (empty($feeList) || empty($merchantId)) return $feeList;
        $feeCodeList = $this->merchantFeeConfigModel->newQuery()->where('merchant_id', $merchantId)->pluck('fee_code')->toArray();
        if (!empty($feeCodeList)) {
            $feeList = collect($feeList)->filter(function ($fee, $key) use ($feeCodeList) {
                return !in_array($fee['code'], $feeCodeList);
            })->toArray();
        }
        return array_values($feeList);
    }


    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     * @throws \Exception
     */
    public function store($params)
    {
        $this->check($params);
        $params['password'] = Hash::make(BaseConstService::INITIAL_PASSWORD);
        $merchant = parent::create($params);
        if ($merchant === false) {
            throw new BusinessLogicException('新增失败,请重新操作');
        }
        //生成授权api
        $id = $merchant->getAttribute('id');
        $merchantApi = $this->getMerchantApiService()->create([
            'merchant_id' => $id,
            'key' => Hashids::encode(time() . $id),
            'secret' => Hashids::connection('alternative')->encode(time() . $id)
        ]);
        if ($merchantApi === false) {
            throw new BusinessLogicException('新增失败,请重新操作');
        }
        $rowCount = MerchantGroup::query()->where('id', $params['merchant_group_id'])->increment('count');
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败');
        }
        //费用配置添加
        $this->addFeeConfigList($id, $params);
        //新增商户所有线路范围
        $this->getLineService()->storeAllPostCodeLineRangeByMerchantId($id);
    }

    /**
     * 批量新增费用配置列表
     * @param $merchantId
     * @param $params
     * @throws BusinessLogicException
     */
    private function addFeeConfigList($merchantId, $params)
    {
        $rowCount = $this->merchantFeeConfigModel->newQuery()->where('merchant_id', $merchantId)->delete();
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        if (empty($params['fee_code_list'])) return;
        $feeCodeList = array_unique(explode(',', $params['fee_code_list']));
        $feeList = $this->getFeeService()->getList(['code' => ['in', $feeCodeList]], ['id', 'code'], false)->toArray();
        if (empty($feeList)) return;
        $newFeeList = [];
        foreach ($feeList as $fee) {
            $newFeeList[] = ['fee_code' => $fee['code']];
        }
        data_set($newFeeList, '*.merchant_id', $merchantId);
        $rowCount = $this->merchantFeeConfigModel->insertAll($newFeeList);
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
        $this->check($data);
        $info = $this->getInfo(['id' => $id], ['merchant_group_id'], false);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        $info = $info->toArray();
        //若修改了商户组,则调整成员
        if (intval($info['merchant_group_id']) !== intval($data['merchant_group_id'])) {
            MerchantGroup::query()->where('id', $info['merchant_group_id'])->decrement('count');
            MerchantGroup::query()->where('id', $data['merchant_group_id'])->increment('count');
        }
        //新增费用配置列表
        $this->addFeeConfigList($id, $data);
    }

    /**
     * 验证
     * @param $params
     * @throws BusinessLogicException
     */
    public function check(&$params)
    {
        if (!empty($params['advance_days']) && !empty($params['appointment_days']) && (intval($params['advance_days']) >= intval($params['appointment_days']))) {
            throw new BusinessLogicException('可预约天数必须大于提前下单天数');
        }
        $merchantGroup = $this->getMerchantGroupService()->getInfo(['id' => $params['merchant_group_id']], ['*'], false);
        if (empty($merchantGroup)) {
            throw new BusinessLogicException('商户组不存在');
        }
        if (empty($params['appointment_days'])) {
            $params['appointment_days'] = null;
        }
        $params['country'] = CompanyTrait::getCountry();
    }

    /**
     * 修改密码
     * @param $id
     * @param $data
     * @throws BusinessLogicException
     */
    public function updatePassword($id, $data)
    {
        $rowCount = parent::updateById($id, ['password' => Hash::make($data['password'])]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }

    /**
     * 批量启用禁用
     * @param $data
     * @throws BusinessLogicException
     */
    public function statusByList($data)
    {
        $ids = json_decode($data['ids'], true);
        for ($i = 0; $i < count($ids); $i++) {
            $rowCount[$i] = parent::updateById($ids[$i], ['status' => $data['status']]);
            if ($rowCount === false) {
                throw new BusinessLogicException('修改失败，请重新操作');
            }
        }
    }

    /**
     * 组内商家
     * @param $group_id
     * @return mixed
     */
    public function indexOfMerchant($group_id)
    {
        $this->query->where('merchant_group_id', $group_id);
        return parent::getPaginate();
    }

    /**
     * @return array
     * @throws BusinessLogicException
     */
    public function merchantExcel()
    {
        $cellData = [];
        $info = $this->getList([], $this->headings, false)->toArray();
        for ($i = 0; $i < count($info); $i++) {
            $info[$i]['merchant_group_id'] = $this->getMerchantGroupService()->getInfo(['id' => $info[$i]['merchant_group_id']], ['name'], false)->toArray()['name'];
            $info[$i]['type'] = $info[$i]['type_name'];
            $info[$i]['settlement_type'] = $info[$i]['settlement_type_name'];
            $info[$i]['status'] = $info[$i]['status_name'];
            $info[$i]['country'] = $info[$i]['country_name'];
            for ($j = 0; $j < count($this->headings); $j++) {
                $cellData[$i][$j] = array_values(Arr::only($info[$i], $this->headings))[$j];
            }

        }
        return $this->excelExport('merchant', $this->headings, $cellData, 'merchant');
    }

    /**
     * 获取商户列表
     * @param $where
     * @return mixed
     */
    public function getMerchantPageList($where)
    {
        $this->filters = $where;
        return parent::getPageList();
    }

}
