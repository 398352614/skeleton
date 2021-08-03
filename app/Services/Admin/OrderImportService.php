<?php
/**
 * Created by PhpStorm
 * User: Yomi
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\OrderImportInfoResource;
use App\Http\Resources\Api\Admin\OrderImportResource;
use App\Http\Validate\Api\Admin\OrderImportValidate;
use App\Http\Validate\BaseValidate;
use App\Models\Merchant;
use App\Models\OrderImportLog;
use App\Models\Package;
use App\Services\ApiServices\TourOptimizationService;
use App\Services\BaseConstService;
use App\Services\CommonService;
use App\Traits\AddressTrait;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ExportTrait;
use App\Traits\ImportTrait;
use App\Traits\LocationTrait;
use Doctrine\DBAL\Driver\OCI8\Driver;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;
use PHPUnit\Framework\Constraint\Count;
use WebSocket\Base;

class OrderImportService extends BaseService
{
    use ExportTrait, ImportTrait, AddressTrait;

    public function __construct(OrderImportLog $orderImportLog)
    {
        parent::__construct($orderImportLog, OrderImportResource::class, OrderImportInfoResource::class);
    }

    public static $headings = [
        [
            "base", "", "", "", "",
            "sender", "", "", "", "", "", "", "",
            "receiver", "", "", "", "", "", "", "",
            "amount", "", "", "", "", "", "", "", "", "", "",
            "settlement", "",
            "other", "", "", "", "",
            "package_1", "", "", "", "", "", "",
            "package_2", "", "", "", "", "", "",
            "package_3", "", "", "", "", "", "",
            "package_4", "", "", "", "", "", "",
            "package_5", "", "", "", "", "", "",
            "material_1", "", "", "", "", "", "", "", "", "",
            "material_2", "", "", "", "", "", "", "", "", "",
            "material_3", "", "", "", "", "", "", "", "", "",
            "material_4", "", "", "", "", "", "", "", "", "",
            "material_5", "", "", "", "", "", "", "", "", "",
        ],
        [
            "create_date", "type", "merchant", "out_user_id", "out_order_no",
            "place_fullname", "place_phone", "place_country_name", "place_post_code", "place_house_number", "place_city", "place_street", "execution_date",
            "second_place_fullname", "second_place_phone", "second_place_country_name", "second_place_post_code", "second_place_house_number", "second_place_city", "second_place_street", "second_execution_date",
            "amount_1", "amount_2", "amount_3", "amount_4", "amount_5", "amount_6", "amount_7", "amount_8", "amount_9", "amount_10", "amount_11",
            "settlement_amount", "settlement_type",
            "control_mode", "receipt_type", "receipt_count", "special_remark", "mask_code",
            "package_no_1", "package_name_1", "package_weight_1", "package_feature_1", "package_remark_1", "package_expiration_date_1", "package_out_order_no_1",
            "package_no_2", "package_name_2", "package_weight_2", "package_feature_2", "package_remark_2", "package_expiration_date_2", "package_out_order_no_2",
            "package_no_3", "package_name_3", "package_weight_3", "package_feature_3", "package_remark_3", "package_expiration_date_3", "package_out_order_no_3",
            "package_no_4", "package_name_4", "package_weight_4", "package_feature_4", "package_remark_4", "package_expiration_date_4", "package_out_order_no_4",
            "package_no_5", "package_name_5", "package_weight_5", "package_feature_5", "package_remark_5", "package_expiration_date_5", "package_out_order_no_5",
            "material_code_1", "material_name_1", "material_count_1", "material_weight_1", "material_size_1", "material_type_1", "material_pack_type_1", "material_price_1", "material_remark_1", "material_out_order_no_1",
            "material_code_2", "material_name_2", "material_count_2", "material_weight_2", "material_size_2", "material_type_2", "material_pack_type_2", "material_price_2", "material_remark_2", "material_out_order_no_2",
            "material_code_3", "material_name_3", "material_count_3", "material_weight_3", "material_size_3", "material_type_3", "material_pack_type_3", "material_price_3", "material_remark_3", "material_out_order_no_3",
            "material_code_4", "material_name_4", "material_count_4", "material_weight_4", "material_size_4", "material_type_4", "material_pack_type_4", "material_price_4", "material_remark_4", "material_out_order_no_4",
            "material_code_5", "material_name_5", "material_count_5", "material_weight_5", "material_size_5", "material_type_5", "material_pack_type_5", "material_price_5", "material_remark_5", "material_out_order_no_5"
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
        return $this->excelExport('template', self::$headings, $cellData, 'order');
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
        //表头验证
        $firstHeadings = array_values(__('excel.order.0'));
        $secondHeadings = array_values(__('excel.order.1'));
        foreach ($row[1] as $k => $v) {
            $row[1][$k] = preg_replace('/\(.*\)/', '', $v);
        }
        $newRow = [];
        foreach ($row[0] as $k => $v) {
            if ($v !== null) {
                $newRow[] = $v;
            }
        }
        if ($newRow !== $firstHeadings || array_diff($row[1], $secondHeadings) !== []) {
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
        //检验货主
        $merchant = $this->getMerchantService()->getInfo(['id' => $data['merchant_id'], 'status' => BaseConstService::MERCHANT_STATUS_1], ['*'], false);
        if (empty($merchant)) {
            $error['merchant_id'] = __('货主不存在');
        }

        //包裹材料验证
        if (empty($data['package_no_1']) && empty($data['material_code_1'])) {
            $error['log'] = __('订单中必须存在一个包裹或一种货物');
        }
        if (CompanyTrait::getAddressTemplateId() == 1 && empty($data['place_address']) && $data['type'] != BaseConstService::ORDER_TYPE_2) {
            $data['place_address'] = CommonService::addressFieldsSortCombine($data, ['place_country', 'place_city', 'place_street', 'place_house_number', 'place_post_code']);
        }
        if (CompanyTrait::getAddressTemplateId() == 1 && empty($data['second_place_address']) && $data['type'] != BaseConstService::ORDER_TYPE_1) {
            $data['second_place_address'] = CommonService::addressFieldsSortCombine($data, ['second_place_country', 'second_place_city', 'second_place_street', 'second_place_house_number', 'second_place_post_code']);
        }
        //填充地址
        try {
            $data = $this->fillAddress($data);
        } catch (BusinessLogicException $e) {
            $error['log'] = __($e->getMessage());
        }
        //若存在货号,则判断是否存在已预约的订单号
        if (!empty($data['out_order_no'])) {
            $where = ['out_order_no' => $data['out_order_no'], 'status' => ['not in', [BaseConstService::ORDER_STATUS_4, BaseConstService::TRACKING_ORDER_STATUS_5]]];
            !empty($orderNo) && $where['order_no'] = ['<>', $orderNo];
            $dbOrder = $this->getOrderService()->getInfo($where, ['id', 'order_no', 'out_order_no', 'status'], false);
            if (!empty($dbOrder)) {
                $list['error']['out_order_no'] = __('外部订单号已存在');
            }
        }

        for ($j = 0; $j < 5; $j++) {
            //包裹重复性判断
            $package[$j] = Package::query()->where('express_first_no', $data['package_no_' . ($j + 1)])
                ->whereNotIn('status', [BaseConstService::PACKAGE_STATUS_4, BaseConstService::PACKAGE_STATUS_5])->first();
            if (!empty($package[$j])) {
                $list['error']['package_no_' . ($j + 1)] = __('包裹') . ($j + 1) . __('编号有重复');
            }
            if (!empty($data['package_no_' . $j]) && empty($data['package_weight_' . $j])) {
                $data['package_weight_' . $j] = 1;
            }
            //有效期判断
            if (!empty($data['package_no_' . $j]) && !empty($data['package_expiration_date_' . ($j + 1)]) && $data['package_expiration_date_' . ($j + 1)] < $data['execution_date']) {
                $list['error'] = __('有效日期不得小于取派日期');
            }
        }
        try {
            //检查网点
            if ($data['type'] == BaseConstService::ORDER_TYPE_1) {
                $this->getLineService()->getInfoByRule($data, BaseConstService::TRACKING_ORDER_OR_BATCH_1, BaseConstService::YES);
                $this->getTrackingOrderService()->fillWarehouseInfo($data, BaseConstService::NO);
            } elseif ($data['type'] == BaseConstService::ORDER_TYPE_2) {
                $address = $this->getBaseWarehouseService()->pieAddress($data, 2);
                $newData = array_merge($data, $address);
                $this->getTrackingOrderService()->fillWarehouseInfo($newData, BaseConstService::NO);
            }
            //运价计算
            if (config('tms.true_app_env') == 'develop' || empty(config('tms.true_app_env'))) {
                $data['distance'] = 1000;
            } elseif ($data['type'] == BaseConstService::ORDER_TYPE_2) {
                $data['distance'] = TourOptimizationService::getDistanceInstance(auth()->user()->company_id)->getDistanceByOrder($newData);
            } else {
                $data['distance'] = TourOptimizationService::getDistanceInstance(auth()->user()->company_id)->getDistanceByOrder($data);
            }
            $data = $this->getTransportPriceService()->priceCount($data);
            //费用总计
            $totalAmount = 0;
            for ($i = 0; $i < 11; $i++) {
                if (!empty($data['amount_' . ($i + 1)]) & !in_array($i + 1, [BaseConstService::ORDER_AMOUNT_TYPE_2, BaseConstService::ORDER_AMOUNT_TYPE_10, BaseConstService::ORDER_AMOUNT_TYPE_11])) {
                    $totalAmount = $totalAmount + $data['amount_' . ($i + 1)];
                }
            }
            $data['expect_total_amount'] = $totalAmount + $data['count_settlement_amount'];
        } catch (BusinessLogicException $e) {
            $error['log'] = __($e->getMessage(), $e->replace);
        }
        if (!empty($error)) {
            $status = BaseConstService::NO;
        } else {
            $status = BaseConstService::YES;
        }
        if (!empty($error['merchant_id'])) {
            $error['merchant'] = $error['merchant_id'];
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
        $place = ["place_fullname", "place_phone", "place_post_code", "place_house_number", "place_city", "place_street", "place_lon", "place_lat"];
        $secondPlace = ["second_place_fullname", "second_place_phone", "second_place_post_code", "second_place_house_number", "second_place_city", "second_place_street", "second_execution_date", "second_place_lon", "second_place_lat"];
        if ($data['type'] == BaseConstService::ORDER_TYPE_1) {
            foreach ($place as $k => $v) {
                if (empty($data[$v])) {
                    $data = $this->fillPlaceAddress($data);
                    break;
                }
            }
        } elseif ($data['type'] == BaseConstService::ORDER_TYPE_2) {
            foreach ($secondPlace as $k => $v) {
                if (empty($data[$v])) {
                    $data = $this->fillSecondPlaceAddress($data);
                    break;
                }
            }
        } elseif ($data['type'] == BaseConstService::ORDER_TYPE_3) {
            foreach ($place as $k => $v) {
                if (empty($data[$v])) {
                    $data = $this->fillPlaceAddress($data);
                    break;
                }
            }
            foreach ($secondPlace as $k => $v) {
                if (empty($data[$v])) {
                    $data = $this->fillSecondPlaceAddress($data);
                    break;
                }
            }
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
        } else {
            $data['place_lon'] = $address['place_lon'];
            $data['place_lat'] = $address['place_lat'];
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
        //反转参数
        $address = $this->getBaseWarehouseService()->pieAddress($data, 2);
        $newData = array_merge($data, $address);
        $newData = $this->fillPlaceAddress($newData);
        //将结果反转
        $data['second_place_province'] = $newData['place_province'];
        $data['second_place_city'] = $newData['place_city'];
        $data['second_place_district'] = $newData['place_district'];
        $data['second_place_street'] = $newData['place_street'];
        $data['second_place_house_number'] = $newData['place_house_number'];
        $data['second_place_post_code'] = $newData['place_post_code'];
        $data['second_place_lat'] = $newData['place_lat'];
        $data['second_place_lon'] = $newData['place_lon'];
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
        $orderTypeList = array_flip(ConstTranslateTrait::orderTypeList());
        $orderSettlementList = array_flip(ConstTranslateTrait::orderSettlementTypeList());
        $controlModeList = array_flip(ConstTranslateTrait::orderControlModeList());
        $receiptTypeList = array_flip(ConstTranslateTrait::orderReceiptTypeList());
        $packageFeatureList = array_flip(ConstTranslateTrait::packageFeatureList());
        $materialTypeList = array_flip(ConstTranslateTrait::materialTypeList());
        $materialPackTypeList = array_flip(ConstTranslateTrait::materialPackTypeList());
        for ($i = 0; $i < count($data); $i++) {
            //反向翻译
            $data[$i]['merchant_id'] = Merchant::query()->where('name', $data[$i]['merchant'])->first()['id'] ?? $data[$i]['merchant'];
            $data[$i]['place_country'] = CountryTrait::getShort($data[$i]['place_country_name']) ?? $data[$i]['place_country_name'];
            $data[$i]['second_place_country'] = CountryTrait::getShort($data[$i]['second_place_country_name']) ?? $data[$i]['second_place_country_name'];
            if (!empty($data[$i]['type'])) {
                $data[$i]['type_name'] = $data[$i]['type'];
                $data[$i]['type'] = $orderTypeList[$data[$i]['type']];
            }
            if (!empty($data[$i]['settlement_type'])) {
                $data[$i]['settlement_type_name'] = $data[$i]['settlement_type'];
                $data[$i]['settlement_type'] = $orderSettlementList[$data[$i]['settlement_type']];
            }
            if (!empty($data[$i]['control_mode'])) {
                $data[$i]['control_mode_name'] = $data[$i]['control_mode'];
                $data[$i]['control_mode'] = $controlModeList[$data[$i]['control_mode']];
            }
            if (!empty($data[$i]['receipt_type'])) {
                $data[$i]['receipt_type_name'] = $data[$i]['receipt_type'];
                $data[$i]['receipt_type'] = $receiptTypeList[$data[$i]['receipt_type']];
            }
            for ($j = 0; $j < 5; $j++) {
                if (!empty($data[$i]['package_feature_' . ($j + 1)])) {
                    $data[$i]['package_feature_' . ($j + 1) . '_name'] = $data[$i]['package_feature_' . ($j + 1)];
                    $data[$i]['package_feature_' . ($j + 1)] = $packageFeatureList[$data[$i]['package_feature_' . ($j + 1)]];
                }
                if (!empty($data[$i]['material_type_' . ($j + 1)])) {
                    $data[$i]['material_type_' . ($j + 1) . '_name'] = $data[$i]['material_type_' . ($j + 1)];
                    $data[$i]['material_type_' . ($j + 1)] = $materialTypeList[$data[$i]['material_type_' . ($j + 1)]];
                }
                if (!empty($data[$i]['material_pack_type_' . ($j + 1)])) {
                    $data[$i]['material_pack_type_' . ($j + 1) . '_name'] = $data[$i]['material_pack_type_' . ($j + 1)];
                    $data[$i]['material_pack_type_' . ($j + 1)] = $materialPackTypeList[$data[$i]['material_pack_type_' . ($j + 1)]];
                }
                is_numeric($data[$i]['package_expiration_date_' . ($j + 1)]) && $data[$i]['package_expiration_date_' . ($j + 1)] = date('Y-m-d', ($data[$i]['package_expiration_date_' . ($j + 1)] - 25569) * 24 * 3600);
            }
            //日期如果是excel时间格式，转换成短横连接格式
            is_numeric($data[$i]['execution_date']) && $data[$i]['execution_date'] = date('Y-m-d', ($data[$i]['execution_date'] - 25569) * 24 * 3600);
            is_numeric($data[$i]['create_date']) && $data[$i]['create_date'] = date('Y-m-d', ($data[$i]['create_date'] - 25569) * 24 * 3600);
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
        $data['package_list'] = [];
        $data['material_list'] = [];
        if ($data['type'] == BaseConstService::ORDER_TYPE_2) {
            $data['place_fullname'] = $data['second_place_fullname'] ?? '';
            $data['place_phone'] = $data['second_place_phone'] ?? '';
            $data['place_country'] = $data['second_place_country'] ?? '';
            $data['place_province'] = $data['second_place_province'] ?? '';
            $data['place_city'] = $data['second_place_city'];
            $data['place_district'] = $data['second_place_district'] ?? '';
            $data['place_street'] = $data['second_place_street'];
            $data['place_house_number'] = $data['second_place_house_number'];
            $data['place_post_code'] = $data['second_place_post_code'];
            $data['place_lat'] = $data['second_place_lat'] ?? '';
            $data['place_lon'] = $data['second_place_lon'] ?? '';
            $data['place_address'] = $data['second_place_address'] ?? '';
            $data['execution_date'] = $data['second_execution_date'];
        }
        if ($data['type'] == BaseConstService::ORDER_TYPE_2) {
            $newData = $this->getAddressService()->secondPlaceToPlace($data);
            $this->getTrackingOrderService()->fillWarehouseInfo($newData, BaseConstService::NO);
            $data = $this->getAddressService()->warehouseToPlace($newData, $data);
            $data = $this->getAddressService()->changePlaceAndSecondPlace($data);
        } elseif ($data['type'] == BaseConstService::ORDER_TYPE_1) {
            $newData = $data;
            $this->getTrackingOrderService()->fillWarehouseInfo($newData, BaseConstService::NO);
            $data = $this->getAddressService()->warehouseToSecondPlace($newData, $data);
        }

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
            "create_date", "type", "merchant_id", "out_user_id", "out_order_no",
            "place_fullname", "place_phone", "place_country", "place_post_code", "place_house_number", "place_city", "place_street", "place_lon", "place_lat", "execution_date", "place_address",
            "second_place_fullname", "second_place_phone","second_place_country", "second_place_post_code", "second_place_house_number", "second_place_city", "second_place_street", "second_execution_date", "second_place_lon", "second_place_lat", "second_place_address",
            "settlement_amount", "settlement_type",
            "control_mode", "receipt_type", "receipt_count", "special_remark", "mask_code",

            'amount_list',
            'package_list',
            'material_list',
        ]);
        return $data;
    }
}
