<?php
/**
 * Created by PhpStorm
 * User: Yomi
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\OrderImportInfoResource;
use App\Http\Resources\Api\Admin\OrderImportResource;
use App\Http\Validate\Api\Merchant\OrderImportValidate;
use App\Http\Validate\BaseValidate;
use App\Models\Merchant;
use App\Models\OrderImportLog;
use App\Models\Package;
use App\Services\ApiServices\TourOptimizationService;
use App\Services\BaseConstService;
use App\Services\CommonService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ExportTrait;
use App\Traits\ImportTrait;
use App\Traits\LocationTrait;
use Doctrine\DBAL\Driver\OCI8\Driver;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderImportService extends BaseService
{
    use ExportTrait, ImportTrait;

    public function __construct(OrderImportLog $orderImportLog)
    {
        parent::__construct($orderImportLog, OrderImportResource::class, OrderImportInfoResource::class);
    }

    public static $headings = [
        [
            "base",
            "other", "", "",
            "sender", "", "", "", "", "", "", "",
            "receiver", "", "", "", "", "", "", "",
            "package_1", "", "", "", "", "",
            "package_2", "", "", "", "", "",
            "package_3", "", "", "", "", "",
            "package_4", "", "", "", "", "",
            "package_5", "", "", "", "", "",
            "material_1", "", "", "", "", "", "", "", "",
            "material_2", "", "", "", "", "", "", "", "",
            "material_3", "", "", "", "", "", "", "", "",
            "material_4", "", "", "", "", "", "", "", "",
            "material_5", "", "", "", "", "", "", "", "",
        ],
        [
            "type", "out_user_id", "out_order_no", "special_remark",
            "place_fullname", "place_phone", "place_country_name", "place_post_code", "place_house_number", "place_city", "place_street", "execution_date",
            "second_place_fullname", "second_place_phone", "second_place_country_name", "second_place_post_code", "second_place_house_number", "second_place_city", "second_place_street", "second_execution_date",
            "package_no_1", "package_name_1", "package_weight_1", "package_feature_1", "package_remark_1", "package_expiration_date_1",
            "package_no_2", "package_name_2", "package_weight_2", "package_feature_2", "package_remark_2", "package_expiration_date_2",
            "package_no_3", "package_name_3", "package_weight_3", "package_feature_3", "package_remark_3", "package_expiration_date_3",
            "package_no_4", "package_name_4", "package_weight_4", "package_feature_4", "package_remark_4", "package_expiration_date_4",
            "package_no_5", "package_name_5", "package_weight_5", "package_feature_5", "package_remark_5", "package_expiration_date_5",
            "material_code_1", "material_name_1", "material_count_1", "material_weight_1", "material_size_1", "material_type_1", "material_pack_type_1", "material_price_1", "material_remark_1",
            "material_code_2", "material_name_2", "material_count_2", "material_weight_2", "material_size_2", "material_type_2", "material_pack_type_2", "material_price_2", "material_remark_2",
            "material_code_3", "material_name_3", "material_count_3", "material_weight_3", "material_size_3", "material_type_3", "material_pack_type_3", "material_price_3", "material_remark_3",
            "material_code_4", "material_name_4", "material_count_4", "material_weight_4", "material_size_4", "material_type_4", "material_pack_type_4", "material_price_4", "material_remark_4",
            "material_code_5", "material_name_5", "material_count_5", "material_weight_5", "material_size_5", "material_type_5", "material_pack_type_5", "material_price_5", "material_remark_5",
        ]
    ];

    /**
     * 模板导出
     * @return array
     * @throws BusinessLogicException
     */
    public function templateExport()
    {
        $cellData[0] = [];
        return $this->excelExport('merchantOrderTemplate', self::$headings, $cellData, 'merchantOrder');
    }

    /**
     * 导入表格
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function import($params)
    {
        //文件读取
        $params['dir'] = 'order';
        $params['path'] = $this->getUploadService()->fileUpload($params)['path'];
        $params['path'] = str_replace(env('APP_URL') . '/storage/', 'public//', $params['path']);
        $row = collect($this->orderExcelImport($params['path'])[0])->whereNotNull('0')->toArray();
        $row = array_values($row);
        //表头验证
        $firstHeadings = array_values(__('excel.merchantOrder.0'));
        $secondHeadings = array_values(__('excel.merchantOrder.1'));
        foreach ($row[1] as $k => $v) {
            if (!empty($v)) {
                $row[1][$k] = preg_replace('/\(.*\)/', '', $v);
            }
        }
        $importHeadings = $importHeadings2 = [];
        foreach ($row[0] as $k => $v) {
            if ($v !== null) {
                $importHeadings[] = $v;
            }
        }
        foreach ($row[1] as $k => $v) {
            if ($v !== null) {
                $importHeadings2[] = $v;
            }
        }
        if ($importHeadings !== $firstHeadings || array_diff($importHeadings2, $secondHeadings) !== []) {
            throw new BusinessLogicException('表格格式不正确，请使用正确的模板导入');
        }
        if (count($row) < 3) {
            throw new BusinessLogicException('模板内无有效数据');
        }
        $newRow = [];
        foreach ($row as $k => $v) {
            for ($i = 0, $j = count($row[0]); $i < $j; $i++) {
                $newRow[$k][$i] = $row[$k][$i];
            }
        }
        $newRow = array_values($newRow);
        //数量验证
        if (count($row) > 202) {
            throw new BusinessLogicException('导入订单数量不得超过200个');
        }
        return $this->importForm($newRow);
    }

    /**
     * 批量导入验证
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function importCheck($params)
    {
        $info = [];
        $package = [];
        $material = [];
        if (empty($params['list'])) {
            throw new BusinessLogicException('数据不能为空');
        }
        $list = json_decode($params['list'], true);
        for ($i = 0, $j = count($list); $i < $j; $i++) {
            $list[$i] = $this->check($list[$i]);
            for ($k = 0; $k < 5; $k++) {
                if ($list[$i]['data']['package_no_' . ($k + 1)]) {
                    if (in_array($list[$i]['data']['package_no_' . ($k + 1)], $package)) {
                        $info[$i]['error']['package_no_' . ($k + 1)] = __('包裹') . ($k + 1) . __('编号有重复');
                    }
                    $package[] = $list[$i]['data']['package_no_' . ($k + 1)];
                }
                if ($list[$i]['data']['material_code_' . ($k + 1)]) {
                    if (in_array($list[$i]['data']['material_code_' . ($k + 1)], $material)) {
                        $info[$i]['error']['material_code_' . ($k + 1)] = __('货物') . ($k + 1) . __('编号有重复');
                    }
                    $material[] = $list[$i]['data']['material_code_' . ($k + 1)];
                }
            }
        }
        return $list;
    }

    /**
     * 单条导入验证
     * @param $data
     * @return array
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function check($data)
    {
        $data['merchant_id'] = auth()->user()->id;
        $data['settlement_type'] = auth()->user()->settlement_type;
        $error = [];
        $validate = new OrderImportValidate;
        foreach ($validate->message as $k => $v) {
            $validate->message[$k] = __($v);
        }
        $validator = Validator::make($data, $validate->rules, array_merge(BaseValidate::$baseMessage, $validate->message));
        if ($validator->fails()) {
            $key = $validator->errors()->keys();
            foreach ($key as $v) {
                $error[$v] = $validator->errors()->first($v);
            }
        }
        if (in_array($data['type'], [BaseConstService::ORDER_TYPE_1, BaseConstService::ORDER_TYPE_3]) && (empty($data['place_country']) || $data['place_country'] !== 'NL')) {
            if (empty($data['place_street'])) {
                $error['place_street'] = __('街道 是必填项');
            }
            if (empty($data['place_city'])) {
                $error['place_city'] = __('城市 是必填项');
            }
        }
        if (in_array($data['type'], [BaseConstService::ORDER_TYPE_2, BaseConstService::ORDER_TYPE_3]) && (empty($data['second_place_country']) || $data['second_place_country'] !== 'NL')) {
            if (empty($data['second_place_street'])) {
                $error['second_place_street'] = __('街道 是必填项');
            }
            if (empty($data['second_place_city'])) {
                $error['second_place_city'] = __('城市 是必填项');
            }
        }
        //包裹材料验证
        if (empty($data['package_no_1']) && empty($data['material_code_1'])) {
            $error['log'] = __('订单中必须存在一个包裹或一种货物');
        }
        //填充地址
        try {
            $data = $this->fillAddress($data);
            //填充地址
            if ((CompanyTrait::getAddressTemplateId() == 1)) {
                $data['place_address'] = CommonService::addressFieldsSortCombine($data, ['place_country', 'place_city', 'place_street', 'place_house_number', 'place_post_code']);
            }
            if ((CompanyTrait::getAddressTemplateId() == 1)) {
                $data['second_place_address'] = CommonService::addressFieldsSortCombine($data, ['second_place_country', 'second_place_city', 'second_place_street', 'second_place_house_number', 'second_place_post_code']);
            }
            //取件，派件运单填充仓库
            if ($data['type'] == BaseConstService::ORDER_TYPE_2) {
                $newData = $this->getAddressService()->secondPlaceToPlace($data);
                $this->getTrackingOrderService()->fillWarehouseInfo($newData, BaseConstService::NO);
                $data = $this->getAddressService()->warehouseToPlace($newData, $data);
            } elseif ($data['type'] == BaseConstService::ORDER_TYPE_1) {
                $newData = $data;
                $this->getTrackingOrderService()->fillWarehouseInfo($newData, BaseConstService::NO);
                $data = $this->getAddressService()->warehouseToSecondPlace($newData, $data);
            }
        } catch (BusinessLogicException $e) {
            $error['log'] = __($e->getMessage());
        }
        //若存在货号,则判断是否存在已预约的订单号
        if (!empty($data['out_order_no'])) {
            $where = ['out_order_no' => $data['out_order_no'], 'status' => ['not in', [BaseConstService::ORDER_STATUS_4, BaseConstService::TRACKING_ORDER_STATUS_5]]];
            !empty($orderNo) && $where['order_no'] = ['<>', $orderNo];
            $dbOrder = $this->getOrderService()->getInfo($where, ['id', 'order_no', 'out_order_no', 'status'], false);
            if (!empty($dbOrder)) {
                $error['out_order_no'] = __('外部订单号已存在');
            }
        }

        for ($j = 0; $j < 5; $j++) {
            //包裹重复性判断
            $package[$j] = Package::query()->where('express_first_no', $data['package_no_' . ($j + 1)])->whereNotNull('merchant_id')
                ->whereNotIn('status', [BaseConstService::PACKAGE_STATUS_4, BaseConstService::PACKAGE_STATUS_5])->first();
            if (!empty($package[$j])) {
                $error['package_no_' . ($j + 1)] = __('包裹') . ($j + 1) . __('编号有重复');
            }
            if (!empty($data['package_no_' . $j]) && empty($data['package_weight_' . $j])) {
                $data['package_weight_' . $j] = 1;
            }
            //有效期判断
            if (!empty($data['package_no_' . $j]) && !empty($data['package_expiration_date_' . ($j + 1)]) && $data['package_expiration_date_' . ($j + 1)] < $data['execution_date']) {
                $error['log'] = __('有效日期不得小于取派日期');
            }
        }
        try {
            //检查网点
            if ($data['type'] == BaseConstService::ORDER_TYPE_1) {
                $this->getLineService()->getInfoByRule($data, BaseConstService::TRACKING_ORDER_OR_BATCH_1, BaseConstService::YES);
            }
            //运价计算
            if (config('tms.true_app_env') == 'develop' || empty(config('tms.true_app_env'))) {
                $data['distance'] = 1000;
            } else {
                $data['distance'] = TourOptimizationService::getDistanceInstance(auth()->user()->company_id)->getDistanceByOrder($data);
            }
            $data = $this->getTransportPriceService()->priceCount($data);
        } catch (BusinessLogicException $e) {
            $error['log'] = __($e->getMessage(), $e->replace);
        }
        //算钱
        $data['expect_total_amount'] = $data['count_settlement_amount'] ?? 0;
        if (!empty($error)) {
            $status = BaseConstService::NO;
        } else {
            $status = BaseConstService::YES;
        }
        $data = Arr::except($data, ["warehouse_fullname",
            "warehouse_phone",
            "warehouse_country",
            "warehouse_post_code",
            "warehouse_house_number",
            "warehouse_city",
            "warehouse_street",
            "warehouse_address",
            "warehouse_lon",
            "warehouse_lat"]);
        if ($data['type'] == BaseConstService::ORDER_TYPE_1) {
            $data = $this->getAddressService()->unsetSecondPlace($data);
        }
        if ($data['type'] == BaseConstService::ORDER_TYPE_2) {
            $data = $this->getAddressService()->unsetPlace($data);
        }
        return ['status' => $status, 'error' => $error, 'data' => $data];
    }

    /**
     * 填充收发件人地址
     * @param $data
     * @return mixed
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fillAddress($data)
    {
        if ($data['type'] == BaseConstService::ORDER_TYPE_1) {
            $data = $this->fillPlaceAddress($data);
        } elseif ($data['type'] == BaseConstService::ORDER_TYPE_2) {
            $data = $this->fillSecondPlaceAddress($data);
        } elseif ($data['type'] == BaseConstService::ORDER_TYPE_3) {
            $data = $this->fillPlaceAddress($data);
            $data = $this->fillSecondPlaceAddress($data);
        }
        return $data;
    }

    /**
     * 填充取件地址
     * @param $data
     * @return mixed
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fillPlaceAddress($data)
    {
        $address = $this->getAddressService()->getInfoByUnique($data);
        if (empty($address)) {
            $info = LocationTrait::getLocation(
                $data['place_country'],
                $data['place_city'] ?? '',
                $data['place_street'] ?? '',
                $data['place_house_number'] ?? '',
                $data['place_post_code'] ?? ''
            );
            $data['place_city'] = empty($data['place_city']) ? $info['city'] : $data['place_city'];
            $data['place_street'] = empty($data['place_street']) ? $info['street'] : $data['place_street'];
            $data['place_district'] = empty($data['place_district']) ? $info['district'] : $data['place_district'];
            $data['place_province'] = empty($data['place_province']) ? $info['province'] : $data['place_province'];
            $data['place_house_number'] = empty($data['place_house_number']) ? $info['house_number'] : $data['place_house_number'];
            $data['place_post_code'] = empty($data['place_post_code']) ? $info['post_code'] : $data['place_post_code'];
            $data['place_lat'] = empty($data['place_lat']) ? $info['lat'] : $data['place_lat'];
            $data['place_lon'] = empty($data['place_lon']) ? $info['lon'] : $data['place_lon'];
            if ($data['place_country'] == 'NL') {
                $data['place_city'] = $info['city'];
                $data['place_street'] = $info['street'];
            }
        }
        return $data;
    }

    /**
     * 填充派件地址
     * @param $data
     * @return mixed
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fillSecondPlaceAddress($data)
    {
        $newData = $this->getAddressService()->secondPlaceToPlace($data);
        $newData = $this->fillPlaceAddress($newData);
        $data = $this->getAddressService()->placeToSecondPlace($newData, $data);
        return $data;
    }

    /**
     * 格式处理
     * @param $row
     * @return array
     */
    public function importForm($row)
    {
        //将表头和每条数据组合
        $headings = OrderImportService::$headings[1];
        $data = [];
        for ($i = 2; $i < count($row); $i++) {
            $data[$i - 2] = collect($headings)->combine($row[$i])->toArray();
        }
        //数据处理
        $merchantOrderTypeList = array_flip(ConstTranslateTrait::merchantOrderTypeList());
        $packageFeatureList = array_flip(ConstTranslateTrait::packageFeatureList());
        $materialTypeList = array_flip(ConstTranslateTrait::materialTypeList());
        $materialPackTypeList = array_flip(ConstTranslateTrait::materialPackTypeList());
        for ($i = 0; $i < count($data); $i++) {
            //反向翻译
            $data[$i]['place_country'] = CountryTrait::getShort($data[$i]['place_country_name']) ?? $data[$i]['place_country_name'];
            $data[$i]['second_place_country'] = CountryTrait::getShort($data[$i]['second_place_country_name']) ?? $data[$i]['second_place_country_name'];
            if (!empty($data[$i]['type'])) {
                $data[$i]['type_name'] = $data[$i]['type'];
                $data[$i]['type'] = $merchantOrderTypeList[$data[$i]['type']];
            }
            for ($j = 0; $j < 5; $j++) {
                if (!empty($data[$i]['package_feature_' . ($j + 1)])) {
                    $data[$i]['package_feature_' . ($j + 1) . 'name'] = $data[$i]['package_feature_' . ($j + 1)];
                    $data[$i]['package_feature_' . ($j + 1)] = $packageFeatureList[$data[$i]['package_feature_' . ($j + 1)]];
                }
                if (!empty($data[$i]['material_type_' . ($j + 1)])) {
                    $data[$i]['material_type_' . ($j + 1) . 'name'] = $data[$i]['material_type_' . ($j + 1)];
                    $data[$i]['material_type_' . ($j + 1)] = $materialTypeList[$data[$i]['material_type_' . ($j + 1)]];
                }
                if (!empty($data[$i]['material_pack_type_' . ($j + 1)])) {
                    $data[$i]['material_pack_type_' . ($j + 1) . 'name'] = $data[$i]['material_pack_type_' . ($j + 1)];
                    $data[$i]['material_pack_type_' . ($j + 1)] = $materialPackTypeList[$data[$i]['material_pack_type_' . ($j + 1)]];
                }
                is_numeric($data[$i]['package_expiration_date_' . ($j + 1)]) && $data[$i]['package_expiration_date_' . ($j + 1)] = date('Y-m-d', ($data[$i]['package_expiration_date_' . ($j + 1)] - 25569) * 24 * 3600);
            }
            //日期如果是excel时间格式，转换成短横连接格式
            is_numeric($data[$i]['execution_date']) && $data[$i]['execution_date'] = date('Y-m-d', ($data[$i]['execution_date'] - 25569) * 24 * 3600);
            is_numeric($data[$i]['second_execution_date']) && $data[$i]['second_execution_date'] = date('Y-m-d', ($data[$i]['second_execution_date'] - 25569) * 24 * 3600);
            $data[$i] = array_map('strval', $data[$i]);
        }
        return $data;
    }

    /**
     * 订单批量新增
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function createByList($params)
    {
        if (is_string($params['list'])) {
            $list = json_decode($params['list'], true);
        } elseif (is_array($params['list'])) {
            $list = $params['list'];
        } else {
            throw new BusinessLogicException('格式不正确');
        }
        for ($i = 0; $i < count($list); $i++) {
            $list[$i] = $this->form($list[$i]);
            try {
                $this->getOrderService()->store($list[$i], BaseConstService::ORDER_SOURCE_2);
            } catch (BusinessLogicException $e) {
                throw new BusinessLogicException(__('行') . ($i + 1) . ':' . $e->getMessage());
            }
            /*            catch (\Exception $e) {
                            throw new BusinessLogicException(__('行') . ($i + 1) . ':' . $e->getMessage());
                        }*/
        }
        return;
    }

    /**
     * 格式化新增数据
     * @param $data
     * @return mixed
     * @throws BusinessLogicException
     */
    public function form($data)
    {
        //取件，派件运单填充仓库
        if ($data['type'] == BaseConstService::ORDER_TYPE_2) {
            $newData = $this->getAddressService()->secondPlaceToPlace($data);
            $this->getTrackingOrderService()->fillWarehouseInfo($newData, BaseConstService::NO);
            $data = $this->getAddressService()->warehouseToPlace($newData, $data);
        } elseif ($data['type'] == BaseConstService::ORDER_TYPE_1) {
            $newData = $data;
            $this->getTrackingOrderService()->fillWarehouseInfo($newData, BaseConstService::NO);
            $data = $this->getAddressService()->warehouseToSecondPlace($newData, $data);
        }
        $data['package_list'] = [];
        $data['material_list'] = [];
        for ($j = 0; $j < 5; $j++) {
            if (!empty($data['package_no_' . ($j + 1)])) {
                $data['package_list'][$j]['name'] = $data['package_name_' . ($j + 1)] ?? '';
                $data['package_list'][$j]['express_first_no'] = $data['package_no_' . ($j + 1)];
                $data['package_list'][$j]['weight'] = $data['package_weight_' . ($j + 1)] ?? 1;
                if (!empty($data['package_feature_' . ($j + 1)])) {
                    $data['package_list'][$j]['feature_logo'] = ConstTranslateTrait::packageFeatureList($data['package_feature_' . ($j + 1)]);
                } else {
                    $data['package_list'][$j]['feature_logo'] = '';
                }
                $data['package_list'][$j]['remark'] = $data['package_remark_' . ($j + 1)] ?? '';
                $data['package_list'][$j]['expiration_date'] = $data['package_expiration_date_' . ($j + 1)] ?? null;
                $data['package_list'][$j]['out_order_no'] = $data['package_out_order_no_' . ($j + 1)] ?? '';
                $data['package_list'] = array_values($data['package_list']);
            }
            if (!empty($data['material_code_' . ($j + 1)])) {
                $data['material_list'][$j]['name'] = $data['material_name_' . ($j + 1)] ?? '';
                $data['material_list'][$j]['code'] = $data['material_code_' . ($j + 1)];
                $data['material_list'][$j]['count'] = $data['material_count_' . ($j + 1)] ?? 1;
                $data['material_list'][$j]['weight'] = $data['material_weight_' . ($j + 1)] ?? 1;
                $data['material_list'][$j]['size'] = $data['material_size_' . ($j + 1)] ?? 1;
                $data['material_list'][$j]['type'] = $data['material_type_' . ($j + 1)] ?? null;
                $data['material_list'][$j]['pack_type'] = $data['material_pack_type_' . ($j + 1)] ?? null;
                $data['material_list'][$j]['unit_price'] = $data['material_price_' . ($j + 1)] ?? 0;
                $data['material_list'][$j]['remark'] = $data['material_remark_' . ($j + 1)] ?? '';
                $data['material_list'][$j]['out_order_no'] = $data['material_out_order_no_' . ($j + 1)] ?? '';
                $data['material_list'] = array_values($data['material_list']);
            }
        }
        for ($i = 0; $i < 11; $i++) {
            $data['amount_list'][$i]['type'] = $i + 1;
            if (empty($data['amount_' . ($i + 1)])) {
                $data['amount_list'][$i]['expect_amount'] = 0;
            } else {
                $data['amount_list'][$i]['expect_amount'] = $data['amount_' . ($i + 1)];
            }
        }
        $data = Arr::only($data, [
            "type", "out_user_id", "out_order_no",
            "place_fullname", "place_phone", "place_country","place_post_code", "place_house_number", "place_city", "place_street", "place_lon", "place_lat", "execution_date",
            "second_place_fullname", "second_place_phone","second_place_country", "second_place_post_code", "second_place_house_number", "second_place_city", "second_place_street", "second_execution_date", "second_place_lon", "second_place_lat",
            "settlement_amount", "settlement_type",
            "control_mode", "receipt_type", "receipt_count", "special_remark", "mask_code",

            'amount_list',
            'package_list',
            'material_list',
        ]);
        $data['source'] = BaseConstService::ORDER_SOURCE_3;
        return $data;
    }
}
