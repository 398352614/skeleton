<?php
/**
 * 商户列表 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\MerchantResource;
use App\Models\Merchant;
use App\Models\MerchantGroup;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\ExportTrait;
use Illuminate\Hashing\Argon2IdHasher;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class MerchantService extends BaseService
{
    use ExportTrait;
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
    }

    /**
     * 商户api 服务
     * @return MerchantApiService
     */
    private function getMerchantApiService()
    {
        return parent::getInstance(MerchantApiService::class);
    }

    /**
     * 商户组管理 服务
     * @return MerchantGroupService
     */
    private function getMerchantGroupService()
    {
        return self::getInstance(MerchantGroupService::class);
    }

    public function init()
    {
        $data = [];
        $data['settlement_type_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$merchantSettlementTypeList);
        return $data;
    }


    /**
     * 新增
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
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
        MerchantGroup::query()->where('id', $params['merchant_group_id'])->increment('count');
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
    }

    /**
     * 验证
     * @param $params
     * @throws BusinessLogicException
     */
    public function check(&$params)
    {
        $merchantGroup = $this->getMerchantGroupService()->getInfo(['id' => $params['merchant_group_id']], ['*'], false);
        if (empty($merchantGroup)) {
            throw new BusinessLogicException('商户组不存在');
        }
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

}
