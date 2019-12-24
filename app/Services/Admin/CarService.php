<?php

namespace App\Services\Admin;

use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Services\BaseConstService;
use App\Services\BaseService;

class CarService extends BaseService
{
    public $filterRules = [
        'status' => ['=', 'status'],
        'order_no,out_order_no' => ['like', 'keyword']
    ];

    public function __construct(Car $car)
    {
        $this->model = $car;
        $this->query = $this->model::query();
        $this->resource = CarResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    //新增
    public function store()
    {
        return $this->create([
            'car_no'            =>   $this->formData['car_no'],
            'outgoing_time'         =>   $this->formData['outgoing_time'],
            'car_brand_id'          =>   $this->formData['car_brand_id'],
            'car_model_id'          =>   $this->formData['car_model_id'],
            'frame_number'          =>   $this->formData['frame_number'],
            'engine_number'         =>   $this->formData['engine_number'],
            'transmission'          =>   $this->formData['transmission'],
            'fuel_type'         =>   $this->formData['fuel_type'],
            'current_miles'         =>   $this->formData['current_miles'],
            'annual_inspection_data'            =>   $this->formData['annual_inspection_data'],
            'ownership_type'            =>   $this->formData['ownership_type'],
            'received_data'         =>   $this->formData['received_data'],
            'month_road_tax'            =>   $this->formData['month_road_tax'],
            'insurance_company'         =>   $this->formData['insurance_company'],
            'insurance_type'            =>   $this->formData['insurance_type'],
            'month_insurance'           =>   $this->formData['month_insurance'],
            'rent_start_data'           =>   $this->formData['rent_start_data'],
            'rent_end_data'         =>   $this->formData['rent_end_data'],
            'rent_month_fee'            =>   $this->formData['rent_month_fee'],
            'repair'            =>   $this->formData['repair'],
            'remark'            =>   $this->formData['remark'],
            'relate_material'           =>   $this->formData['relate_material'],
        ]);
    }

    public function updateCar($id)
    {
        return $this->updateById($id, [
            'car_no'            =>   $this->formData['car_no'],
            'outgoing_time'         =>   $this->formData['outgoing_time'],
            'car_brand_id'          =>   $this->formData['car_brand_id'],
            'car_model_id'          =>   $this->formData['car_model_id'],
            'frame_number'          =>   $this->formData['frame_number'],
            'engine_number'         =>   $this->formData['engine_number'],
            'transmission'          =>   $this->formData['transmission'],
            'fuel_type'         =>   $this->formData['fuel_type'],
            'current_miles'         =>   $this->formData['current_miles'],
            'annual_inspection_data'            =>   $this->formData['annual_inspection_data'],
            'ownership_type'            =>   $this->formData['ownership_type'],
            'received_data'         =>   $this->formData['received_data'],
            'month_road_tax'            =>   $this->formData['month_road_tax'],
            'insurance_company'         =>   $this->formData['insurance_company'],
            'insurance_type'            =>   $this->formData['insurance_type'],
            'month_insurance'           =>   $this->formData['month_insurance'],
            'rent_start_data'           =>   $this->formData['rent_start_data'],
            'rent_end_data'         =>   $this->formData['rent_end_data'],
            'rent_month_fee'            =>   $this->formData['rent_month_fee'],
            'repair'            =>   $this->formData['repair'],
            'remark'            =>   $this->formData['remark'],
            'relate_material'           =>   $this->formData['relate_material'],
        ]);
    }


}