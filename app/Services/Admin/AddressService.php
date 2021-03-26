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
use App\Models\Address;
use App\Services\CommonService;
use App\Traits\CompanyTrait;
use App\Traits\ExportTrait;
use Illuminate\Support\Arr;

class AddressService extends BaseService
{
    use ExportTrait;

    /**
     * 导出 Excel 头部
     * @var string[]
     */
    public $exportExcelHeader = [
        'place_fullname',
        'place_phone',
        'place_country',
        'place_province',
        'place_post_code',
        'place_house_number',
        'place_city',
        'place_district',
        'place_street',
        'place_address'
    ];

    /**
     * @var \string[][]
     */
    public $filterRules = [
        'merchant_id' => ['=', 'merchant_id'],
        'place_fullname' => ['like', 'place_fullname'],
        'place_post_code' => ['like', 'place_post_code'],
        'type'=>['=','type']
    ];

    /**
     * AddressService constructor.
     * @param  Address  $address
     */
    public function __construct(Address $address)
    {
        parent::__construct($address, AddressResource::class, AddressResource::class);
    }

    /**
     * 获取唯一性条件
     * @param $data
     * @return array
     */
    public function getUniqueWhere($data)
    {
        $fields = ['type','merchant_id', 'place_country', 'place_fullname', 'place_phone', 'place_post_code', 'place_house_number', 'place_city', 'place_street', 'place_address'];
        $where = Arr::only($data, $fields);
        return $where;
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
     * 通过唯一组合字段获取信息
     * @param $data
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getInfoByUnique($data)
    {
        return parent::getInfo($this->getUniqueWhere($data), ['*'], false);
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->check($params);
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败,请重新操作');
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
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $this->check($data, $info->toArray());
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }


    /**
     * 验证
     * @param $data
     * @param array $dbInfo
     * @throws BusinessLogicException
     */
    public function check(&$data, $dbInfo = [])
    {
        $data['place_country'] = !empty($dbInfo['place_country']) ? $dbInfo['place_country'] : CompanyTrait::getCountry();
        //验证商家是否存在
        $merchant = $this->getMerchantService()->getInfo(['id' => $data['merchant_id']], ['id', 'country'], false);
        if (empty($merchant)) {
            throw new BusinessLogicException('货主不存在，请重新选择货主');
        }
        if ((CompanyTrait::getAddressTemplateId() == 1) || empty($data['place_address'])) {
            $data['place_address'] = CommonService::addressFieldsSortCombine($data, ['place_country', 'place_city', 'place_street', 'place_house_number', 'place_post_code']);
        }
        //判断是否唯一
        $where = $this->getUniqueWhere($data);
        !empty($dbInfo['id']) && $where = Arr::add($where, 'id', ['<>', $dbInfo['id']]);
        $info = parent::getInfo($where, ['*'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('地址已存在，不能重复添加');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
    }

    public function importTemplate()
    {

    }

    public function import()
    {

    }

    public function createByList()
    {

    }

    /**
     * @return array
     * @throws BusinessLogicException
     */
    public function export()
    {
        $data = $this->setFilter()->getList();

        if ($data->isEmpty()) {
            throw new BusinessLogicException(__('数据不存在'));
        }

        $cellData = [];
        foreach ($data as $v) {
            $cellData[] = array_only_fields_sort($v, $this->exportExcelHeader);
        }
        if (empty($cellData)) {
            throw new BusinessLogicException(__('数据不存在'));
        }

        $dir = 'addressExcelExport';
        $name = date('YmdHis') . auth()->user()->id;

        return $this->excelExport($name, $this->exportExcelHeader, $cellData, $dir);
    }

}
