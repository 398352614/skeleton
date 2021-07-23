<?php
/**
 * 货主列表 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\MerchantResource;
use App\Models\Merchant;
use App\Models\MerchantGroup;
use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\ExportTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

/**
 * Class MerchantService
 * @package App\Services\Admin
 */
class MerchantService extends BaseService
{
    use ExportTrait;

    public $filterRules = [
        'code,name,email' => ['like', 'keyword'],
        'merchant_group_id' => ['=', 'merchant_group_id'],
        'status' => ['=', 'status'],
        'code' => ['like', 'code'],
        'name' => ['like', 'name'],
        'email' => ['like', 'email'],
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
        $info['merchant_group_name'] = $info['merchant_group']['name'];
        unset($info['password'], $info['merchant_group']);
        return $info;
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
        $params['password'] = Hash::make($params['password'] ?? BaseConstService::INITIAL_PASSWORD);
        $merchant = parent::create($params);
        if ($merchant === false) {
            throw new BusinessLogicException('新增失败，请重新操作');
        }
        //生成用户编码
        $id = $merchant->getAttribute('id');
        $rowCount = parent::updateById($id, ['code' => sprintf("%05s", $id)]);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败，请重新操作');
        }
        $rowCount = MerchantGroup::query()->where('id', $params['merchant_group_id'])->increment('count');
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败');
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
        //若修改了货主组,则调整成员
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
        if (!empty($params['warehouse_id'])) {
            $warehouse = $this->getWareHouseService()->getInfo(['id' => $params['warehouse_id']], ['*'], false);
            if (empty($warehouse)) {
                throw new BusinessLogicException('网点不存在');
            }
            if (strstr($warehouse['acceptance_type'], strval(BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_3)) == false) {
                throw new BusinessLogicException('网点未配置仓配一体，无法选择该网点');
            }
        }else{
            unset($params['warehouse_id']);
        }
        $merchantGroup = $this->getMerchantGroupService()->getInfo(['id' => $params['merchant_group_id']], ['*'], false);
        if (empty($merchantGroup)) {
            throw new BusinessLogicException('货主组不存在');
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

    /**
     * 获取货主列表
     * @param $where
     * @return mixed
     */
    public function getMerchantPageList($where)
    {
        $this->filters = $where;
        return parent::getPageList();
    }

    public function getPageList()
    {
        $this->query->orderByDesc('id');
        return parent::getPageList();
    }
}
