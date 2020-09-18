<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\ConstTranslateTrait;
use App\Traits\ExportTrait;
use App\Traits\PrintTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CarService extends BaseService
{
    use ExportTrait, PrintTrait;

    public $filterRules = [
        'status' => ['=', 'status'],
    ];

    public $headings = [
        'car_no',
        'driver_name',
        'execution_date',
        'begin_distance',
        'end_distance',
        'expect_distance',
        'handmade_actual_distance',
    ];

    public function __construct(Car $car)
    {
        parent::__construct($car, CarResource::class, CarResource::class);
    }

    /**
     * 取件线路服务
     * @return TourService
     */
    public function getTourService()
    {
        return self::getInstance(TourService::class);
    }

    public function init()
    {
        $data = [];
        $data['car_owner_ship_type_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$carOwnerShipTypeList);
        $data['car_fuel_type_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$carFuelTypeList);
        $data['car_transmission_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$carTransmissionList);
        return $data;
    }


    //新增
    public function store()
    {
        return $this->create([
            'car_no' => $this->formData['car_no'],
            'outgoing_time' => $this->formData['outgoing_time'] ?? null,
            'car_brand_id' => $this->formData['car_brand_id'],
            'car_model_id' => $this->formData['car_model_id'],
            'ownership_type' => $this->formData['ownership_type'] ?? 1,
            'insurance_company' => $this->formData['insurance_company'] ?? '',
            'insurance_type' => $this->formData['insurance_type'] ?? '',
            'month_insurance' => $this->formData['month_insurance'] ?? 0.00,
            'rent_start_date' => $this->formData['rent_start_date'] ?? '2020-01-01',
            'rent_end_date' => $this->formData['rent_end_date'] ?? '2020-01-01',
            'rent_month_fee' => $this->formData['rent_month_fee'] ?? 0,
            'repair' => $this->formData['repair'] ?? 1,
            'remark' => $this->formData['remark'] ?? '',
            'relate_material' => $this->formData['relate_material'] ?? '',
            'relate_material_name' => $this->formData['relate_material_name'] ?? '',
            'relate_material_list' => $this->formData['relate_material_list'] ?? '',
        ]);
    }

    /**
     * 修改
     * @param $id
     * @throws BusinessLogicException
     */
    public function updateCar($id)
    {
        $rowCount = $this->updateById($id, [
            'car_no' => $this->formData['car_no'],
            'outgoing_time' => $this->formData['outgoing_time'],
            'car_brand_id' => $this->formData['car_brand_id'],
            'car_model_id' => $this->formData['car_model_id'],
            'ownership_type' => $this->formData['ownership_type'],
            'insurance_company' => $this->formData['insurance_company'],
            'insurance_type' => $this->formData['insurance_type'],
            'month_insurance' => $this->formData['month_insurance'] ?? 0,
            'rent_start_date' => $this->formData['rent_start_date'] ?? '2020-01-01',
            'rent_end_date' => $this->formData['rent_end_date'] ?? '2020-01-01',
            'rent_month_fee' => $this->formData['rent_month_fee'] ?? 0,
            'repair' => $this->formData['repair'],
            'remark' => $this->formData['remark'],
            'relate_material' => $this->formData['relate_material'] ?? '',
            'relate_material_name' => $this->formData['relate_material_name'] ?? '',
            'relate_material_list' => $this->formData['relate_material_list'] ?? '',
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改车辆失败');
        }
    }

    /**
     * 锁定与解锁
     * @param $id
     * @param $isLocked
     * @throws BusinessLogicException
     */
    public function lock($id, $isLocked)
    {
        $rowCount = parent::updateById($id, ['is_locked' => $isLocked]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $tourList = $this->getTourService()->getList(['car_id' => $id], ['*'], false)->toArray();
        foreach ($tourList as $v) {
            if (in_array($v['status'], [BaseConstService::TOUR_STATUS_4, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_2])) {
                throw new BusinessLogicException('仍有未完成的任务，无法删除');
            }
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('车辆删除失败');
        }
    }

    public function getPageList()
    {
        if (!empty($this->formData['tour_no'])) {
            $date = Tour::query()->where('tour_no', $this->formData['tour_no'])->first()->toArray()['execution_date'];
            $info = Tour::query()->where('execution_date', $date)->where('status', '<>', BaseConstService::TOUR_STATUS_5)->whereNotNull('car_id')->pluck('car_id')->toArray();
            if (!empty($info)) {
                $this->query->whereNotIn('id', $info);
            }
            $this->query->where('is_locked', '=', BaseConstService::DRIVER_TO_NORMAL);
        }
        return parent::getPageList();
    }

    /**
     * @param $id
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function distanceExport($id, $params)
    {
        $car = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($car)) {
            throw new BusinessLogicException('数据不存在');
        }
        $data = $this->getTourService()->getList(['car_no' => $car['car_no'], 'status' => BaseConstService::TOUR_STATUS_5, 'execution_date' => $params['execution_date']], ['*'], false);
        foreach ($data as $k => $v) {
            $data[$k]['handmade_actual_distance'] = $v['end_distance'] - $v['begin_distance'];
        }
        $cellData = [];
        foreach ($data as $v) {
            $cellData[] = array_only_fields_sort($v, $this->headings);
        }
        if (empty($cellData)) {
            throw new BusinessLogicException('数据不存在');
        }
        $dir = 'carDistance';
        $name = date('YmdHis') . auth()->user()->id;
        return $this->excelExport($name, $this->headings, $cellData, $dir);
    }

    /**
     * 车辆信息导出
     * @param $id
     * @return array
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function infoExport($id)
    {
        $data = [];
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $data['car_no'] = $info->car_no;
        $data['url_list'] = collect(json_decode($info->relate_material_list))->pluck('url')->toArray();
        foreach ($data['url_list'] as $k => $v) {
            $data['url_list'][$k] = str_replace(env('APP_URL') .'/storage', storage_path('/app/public'), $v);
        }
        $url = PrintTrait::tPrint($data, 'car.car', 'car', null);
        return [
            'name' => $data['car_no'],
            'path' => $url
        ];
    }

}
