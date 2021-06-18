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
use App\Services\BaseConstService;
use App\Services\CommonService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ExportTrait;
use App\Traits\ImportTrait;
use App\Traits\LocationTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AddressService extends BaseService
{
    use ExportTrait;
    use ImportTrait;

    /**
     * @var \string[][]
     */
    public $filterRules = [
        'merchant_id' => ['=', 'merchant_id'],
        'place_fullname,place_phone,place_address' => ['like', 'place_fullname'],
        'place_post_code' => ['like', 'place_post_code'],
        'type' => ['=', 'type']
    ];

    /**
     * 导出 Excel 头部
     * @var string[]
     */
    public $exportExcelHeader = [
        'type',
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
        'type',
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
        $allAddress = '';
        $fields = [
            'type', 'merchant_id', 'place_fullname', 'place_phone',
            'place_country', 'place_province', 'place_city', 'place_district',
            'place_post_code', 'place_street', 'place_house_number',
            'place_address'
        ];
        foreach ($fields as $k => $v) {
            $allAddress = $allAddress . $data[$v];
        }
        $uniqueCode = md5($allAddress);
        $where = ['unique_code' => $uniqueCode];
        return $where;
    }

    /**
     * 获取唯一码
     * @param $data
     * @return string
     */
    public function getUniqueCode($data)
    {
        $allAddress = '';
        $fields = [
            'type', 'merchant_id', 'place_fullname', 'place_phone',
            'place_country', 'place_province', 'place_city', 'place_district',
            'place_post_code', 'place_street', 'place_house_number',
            'place_address'
        ];
        foreach ($fields as $k => $v) {
            $allAddress = $allAddress . ($data[$v] ?? '');
        }
        return md5($allAddress);
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
        return parent::getInfo(['unique_code' => $this->getUniqueCode($data)], ['*'], false);
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
        $this->check($data, $id);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }


    /**
     * 验证
     * @param $data
     * @param null $id
     * @throws BusinessLogicException
     */
    public function check(&$data, $id = null)
    {
        if (auth()->user()->company_id !== 'NL') {
            if (empty($data['place_city'])) {
                throw new BusinessLogicException('城市是必填的');
            }
            if (empty($data['place_street'])) {
                throw new BusinessLogicException('街道是必填的');
            }
        }
        if (empty($data['place_lon']) || empty($data['place_lat'])) {
            throw new BusinessLogicException('地址无法定位，请选择其他地址');
        }
        $fields = ['place_fullname', 'place_phone',
            'place_country', 'place_province', 'place_city', 'place_district',
            'place_post_code', 'place_street', 'place_house_number',
            'place_address'];
        foreach ($fields as $v) {
            array_key_exists($v, $data) && $data[$v] = trim($data[$v]);
        }
        if (!empty($id)) {
            $info = parent::getInfo(['id' => $id], ['*'], false);
            if (empty($info)) {
                throw new BusinessLogicException('数据不存在');
            }
        }

        $data['place_country'] = !empty($dbInfo['place_country']) ? $dbInfo['place_country'] : CompanyTrait::getCountry();
        //验证商家是否存在
        $merchant = $this->getMerchantService()->getInfo(['id' => $data['merchant_id']], ['id', 'country'], false);
        if (empty($merchant)) {
            throw new BusinessLogicException('货主不存在，请重新选择货主');
        }
        if ((CompanyTrait::getAddressTemplateId() == 1) || empty($data['place_address'])) {
            $data['place_address'] = CommonService::addressFieldsSortCombine($data, ['place_country', 'place_city', 'place_street', 'place_house_number', 'place_post_code']);
        }

        $this->uniqueCheck($data, $id);
    }

    /**
     * 唯一性判断
     * @param $data
     * @param null $id
     * @throws BusinessLogicException
     */
    public function uniqueCheck(&$data, $id = null)
    {
        //判断是否唯一
        $data['unique_code'] = $this->getUniqueCode($data);
        if (!empty($id)) {
            $info = parent::getInfo(['unique_code' => $data['unique_code'], 'id' => ['<>', $id]], ['*'], false);
        } else {
            $info = parent::getInfo(['unique_code' => $data['unique_code']], ['*'], false);
        }
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
            __('发件人'),
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
        $params['path'] = str_replace(config('app.url') . '/storage/admin/file', storage_path('app/public/admin/file'), $params['path']);
        Log::info('end-path', $params);
        $row = collect($this->addressExcelImport($params['path'])[0])->whereNotNull('0')->toArray();
        $row[0] = array_filter($row[0]);

        //表头验证
        $headings = array_values(__('excel.addressExcelExport'));
        if ($row[0] !== $headings) {
            throw new BusinessLogicException('表格格式不正确，请使用正确的模板导入');
        }
        if (count($row) < 2) {
            throw new BusinessLogicException('模板内无有效数据');
        }
        $newRow = [];
        foreach ($row as $k => $v) {
            for ($i = 0, $j = count($row[0]); $i < $j; $i++) {
                $newRow[$k][$i] = $row[$k][$i];
            }
        }
        $newRow = array_values($newRow);
        $data = [];
        for ($i = 1; $i < count($newRow); $i++) {
            $data[$i - 1] = collect($this->importExcelHeader)->combine($newRow[$i])->toArray();
        }
        //数据处理
        $countryNameList = array_unique(collect($data)->pluck('place_country')->toArray());
        $countryShortList = CountryTrait::getShortListByName($countryNameList);
        $addressTypeList = array_flip(ConstTranslateTrait::addressTypeList());
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = array_map('strval', $data[$i]);
            //反向翻译
            $data[$i]['place_country_name'] = $data[$i]['place_country'];
            $data[$i]['place_country'] = $countryShortList[$data[$i]['place_country']] ?? $data[$i]['place_country'];
            if (!empty($data[$i]['type'])) {
                $data[$i]['type_name'] = $data[$i]['type'];
                $data[$i]['type'] = $addressTypeList[$data[$i]['type']];
            }
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
            $list[$i]['merchant_id'] = $params['merchant_id'];
            empty($list[$i]['place_country']) && $list[$i]['place_country'] = CompanyTrait::getCountry();
            try {
                $this->store($list[$i]);
            } catch (BusinessLogicException $e) {
                throw new BusinessLogicException(__('行') . ($i + 1) . ':' . $e->getMessage());
            }
        }
    }

    /**
     * 订单格式转换
     * @param $data
     * @return mixed
     */
    public function form($data)
    {
        $data = Arr::only($data, array_merge($this->importExcelHeader, ['place_lon', 'place_lat']));
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

    /**
     * 批量导入验证
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function importCheckByList($params)
    {
        $list = json_decode($params['list'], true);
        for ($i = 0, $j = count($list); $i < $j; $i++) {
            $list[$i] = $this->importCheck($list[$i]);
        }
        return $list;
    }

    /**
     * 单条导入验证
     * @param $data
     * @return array
     * @throws BusinessLogicException
     */
    public function importCheck($data)
    {
        $status = BaseConstService::YES;
        $error['log'] = [];
        $validate = new AddressImportValidate();
        $validator = Validator::make($data, $validate->rules, array_merge(BaseValidate::$baseMessage, $validate->message), $validate->customAttributes);
        if ($validator->fails()) {
            $status = BaseConstService::NO;
            $key = $validator->errors()->keys();
            foreach ($key as $v) {
                $error[$v] = $validator->errors()->first($v);
            }
        }
        //判断是否唯一
        $this->uniqueCheck($data);
        //如果没传经纬度，就通过第三方API获取经纬度
        if (empty($data['place_lon']) || empty($data['place_lat'])) {
            try {
                $info = LocationTrait::getLocation($data['place_country'], $data['place_city'], $data['place_street'], $data['place_house_number'], $data['place_post_code']);
                $data['place_lon'] = $info['lon'] ?? '';
                $data['place_lat'] = $info['lat'] ?? '';
            } catch (BusinessLogicException $e) {
                $status = BaseConstService::NO;
                $error['log'] = __($e->getMessage(), $e->replace);
            } catch (\Exception $e) {
            }
            $data['place_country'] = $data['place_country'] ?? CompanyTrait::getCountry();
            $data['place_post_code'] = $data['place_post_code'] ?? $info['post_code'];
            $data['place_house_number'] = $data['place_house_number'] ?? $info['house_number'];
            $data['place_city'] = empty($data['place_city']) ? $info['city'] : $data['place_city'];
            $data['place_street'] = empty($data['place_street']) ? $info['street'] : $data['place_street'];
            $data['place_district'] = empty($data['place_district']) ? $info['district'] : $data['place_district'];
            $data['place_province'] = empty($data['place_province']) ? $info['province'] : $data['place_province'];
            $data['place_lon'] = $data['place_lon'] ?? '';
            $data['place_lat'] = $data['place_lat'] ?? '';
        }
        return ['status' => $status, 'error' => $error, 'data' => $data];
    }

    public function getPageList()
    {
        $this->query->orderByDesc('id');
        return parent::getPageList();
    }
}
