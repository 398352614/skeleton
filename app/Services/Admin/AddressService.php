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
use App\Services\BaseConstService;
use App\Services\CommonService;
use App\Services\Merchant\OrderImportService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ExportTrait;
use App\Traits\ImportTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AddressService extends BaseService
{
    use ExportTrait;
    use ImportTrait;

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
     * 导入 Excel 头部
     * @var string[]
     */
    public $importExcelHeader = [
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
        'type' => ['=', 'type']
    ];

    /**
     * AddressService constructor.
     * @param Address $address
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
        $fields = ['type', 'merchant_id', 'place_country', 'place_fullname', 'place_phone', 'place_post_code', 'place_house_number', 'place_city', 'place_street', 'place_address'];
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

    /**
     * 生成模板
     * @return array
     * @throws BusinessLogicException
     */
    public function excelTemplate()
    {
        $cellData[0] = [
            __('示例，此行数据不会被导入'),
            __('1XXXXXXXXXX'),
            __('中国'),
            __('湖南省'),
            __('41X000'),
            __('27'),
            __('长沙市'),
            __('岳麓区'),
            __('文轩路'),
            __('麓谷企业广场C8栋808')
        ];
        return $this->excelExport('addressTemplate', $this->exportExcelHeader, $cellData, 'addressExcelExport');
    }

    /**
     * 订单导入
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function import($params)
    {
        //文件验证
        $this->addressImportValidate($params);
        //文件读取
        $params['dir'] = 'addressTemplate';
        $params['path'] = $this->getUploadService()->fileUpload($params)['path'];
        Log::info('begin-path', $params);
        $params['path'] = str_replace(config('app.url') . '/storage/merchant/file', storage_path('app/public/merchant/file'), $params['path']);
        Log::info('end-path', $params);
        $row = collect($this->addressExcelImport($params['path'])[0])->whereNotNull('0')->toArray();
        //表头验证
        $headings = array_values(__('excel.addressExcelExport'));
        if ($row[0] !== $headings) {
            throw new BusinessLogicException('表格格式不正确，请使用正确的模板导入');
        }
        $data = [];
        for ($i = 2; $i < count($row); $i++) {
            $data[$i - 2] = collect($headings)->combine($row[$i])->toArray();
        }
        //数据处理
        $countryNameList = array_unique(collect($data)->pluck('place_country_name')->toArray());
        $countryShortList = CountryTrait::getShortListByName($countryNameList);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = array_map('strval', $data[$i]);
            //反向翻译
            $data[$i]['country'] = $countryShortList[$data[$i]['country']] ?? $data[$i]['country'];
        }
        return $data;
    }

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function addressImportValidate($params)
    {
        //验证$params
        $checkfile = \Illuminate\Support\Facades\Validator::make($params,
            ['file' => 'required|file'],
            ['file.file' => '必须是文件']);
        if ($checkfile->fails()) {
            $error = array_values($checkfile->errors()->getMessages())[0][0];
            throw new BusinessLogicException($error, 301);
        }
    }

    /**
     * 批量新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function createByList($params)
    {
        $list = json_decode($params['list'], true);
        for ($i = 0; $i < count($list); $i++) {
            $list[$i] = $this->form($list[$i]);
            empty($list[$i]['place_country']) && $list[$i]['place_country'] = CompanyTrait::getCountry();
            try {
                $this->store($list[$i]);
            } catch (BusinessLogicException $e) {
                throw new BusinessLogicException(__('行') . ($i + 1) . ':' . $e->getMessage());
            }
        }
    }

    public function importCheck()
    {

    }

    /**
     * 订单格式转换
     * @param $data
     * @return mixed
     */
    public function form($data)
    {
        $data = Arr::only($data, $this->importExcelHeader);
        return $data;
    }

    /**
     * @param $idList
     * @return array
     * @throws BusinessLogicException
     */
    public function export($idList)
    {
        $idList = explode_id_string($idList);

        $data = $this->query->whereIn('id', $idList)->get();

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
