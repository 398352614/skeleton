<?php

namespace App\Http\Resources\Api\Driver;

use App\Services\BaseConstService;
use App\Services\GisService;
use App\Traits\CompanyTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class TourInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge([
            'id' => $this->id,
            'driver_location' => $this->driver_location,
            'company_id' => $this->company_id,
            'tour_no' => $this->tour_no,
            'line_id' => $this->line_id,
            'line_name' => $this->line_name,
            'execution_date' => $this->execution_date,
            'type' => $this->type,
            'type_name' => !empty($this->type_name) ? $this->type_name : __('取派'),
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'driver_phone' => $this->driver_phone,
            'driver_assign_status' => (!empty($this->driver_id) && !empty($this->driver_name)) ? BaseConstService::TOUR_STATUS_2 : BaseConstService::TOUR_STATUS_1,
            'driver_rest_time' => $this->driver_rest_time,
            'driver_avt_id' => $this->driver_avt_id,
            'car_id' => $this->car_id,
            'car_no' => $this->car_no,
            'car_assign_status' => (!empty($this->car_id) && !empty($this->car_no)) ? BaseConstService::TOUR_STATUS_2 : BaseConstService::TOUR_STATUS_1,
            'warehouse_id' => $this->warehouse_id,
            'warehouse_name' => $this->warehouse_name,
            'warehouse_phone' => $this->warehouse_phone,
            'warehouse_country' => $this->warehouse_country,
            'warehouse_post_code' => $this->warehouse_post_code,
            'warehouse_city' => $this->warehouse_city,
            'warehouse_street' => $this->warehouse_street,
            'warehouse_house_number' => $this->warehouse_house_number,
            'warehouse_address' => $this->warehouse_address,
            'warehouse_lon' => $this->warehouse_lon,
            'warehouse_lat' => $this->warehouse_lat,
            'warehouse_expect_time' => $this->warehouse_expect_time,
            'warehouse_expect_distance' => $this->warehouse_expect_distance,
            'warehouse_expect_arrive_time' => $this->warehouse_expect_arrive_time,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'begin_signature' => $this->begin_signature,
            'begin_signature_remark' => $this->begin_signature_remark,
            'begin_signature_first_pic' => $this->begin_signature_first_pic,
            'begin_signature_second_pic' => $this->begin_signature_second_pic,
            'begin_signature_third_pic' => $this->begin_signature_third_pic,
            'end_time' => $this->end_time,
            'end_signature' => $this->end_signature,
            'end_signature_remark' => $this->end_signature_remark,
            'expect_distance' => $this->expect_distance,
            'actual_distance' => $this->actual_distance,
            'expect_time' => $this->expect_time,
            'actual_time' => $this->actual_time,
            'expect_time_human' => $this->expect_time_human,
            'actual_time_human' => $this->actual_time_human,
            'expect_pickup_quantity' => $this->expect_pickup_quantity,
            'actual_pickup_quantity' => $this->actual_pickup_quantity,
            'expect_pie_quantity' => $this->expect_pie_quantity,
            'actual_pie_quantity' => $this->actual_pie_quantity,
            'sticker_amount' => $this->sticker_amount,
            'delivery_amount' => $this->delivery_amount,
            'replace_amount' => $this->replace_amount,
            'remark' => $this->remark,
            'batch_count' => $this->batch_count,
            'batchs' => BatchResource::collection($this->batchs)->sortBy('sort_id')->values(),
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ], GisService::corTransfer(['receiver_lon'=>$this->receiver_lon,'receiver_lat'=>$this->receiver_lat]));
    }
}
