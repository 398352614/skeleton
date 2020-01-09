<?php

namespace App\Http\Resources;

use App\Services\BaseConstService;
use Illuminate\Http\Resources\Json\JsonResource;

class TourInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'driver_location'   => $this->driver_location,
            'company_id' => $this->company_id,
            'tour_no' => $this->tour_no,
            'line_id' => $this->line_id,
            'line_name' => $this->line_name,
            'execution_date' => $this->execution_date,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'driver_assign_status' => (!empty($this->driver_id) && !empty($this->driver_name)) ? BaseConstService::TOUR_STATUS_2 : BaseConstService::TOUR_STATUS_1,
            'driver_rest_time' => $this->driver_rest_time,
            'driver_avt_id' => $this->driver_avt_id,
            'car_id' => $this->car_id,
            'car_no' => $this->car_no,
            'car_assign_status' => (!empty($this->car_id) && !empty($this->car_no)) ? BaseConstService::TOUR_STATUS_2 : BaseConstService::TOUR_STATUS_1,
            'warehouse_id' => $this->warehouse_id,
            'warehouse_name' => $this->warehouse_name,
            'warehouse_phone' => $this->warehouse_phone,
            'warehouse_post_code' => $this->warehouse_post_code,
            'warehouse_city' => $this->warehouse_city,
            'warehouse_address' => $this->warehouse_address,
            'warehouse_lon' => $this->warehouse_lon,
            'warehouse_lat' => $this->warehouse_lat,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'begin_signature' => $this->begin_signature,
            'begin_signature_remark' => $this->begin_signature_remark,
            'begin_signature_first_pic' => $this->begin_signature_first_pic,
            'begin_signature_second_pic' => $this->begin_signature_second_pic,
            'begin_signature_third_pic' => $this->begin_signature_third_pic,
            'end_signature' => $this->end_signature,
            'end_signature_remark' => $this->end_signature_remark,
            'expect_distance' => $this->expect_distance,
            'actual_distance' => $this->actual_distance,
            'expect_pickup_quantity' => $this->expect_pickup_quantity,
            'actual_pickup_quantity' => $this->actual_pickup_quantity,
            'expect_pie_quantity' => $this->expect_pie_quantity,
            'actual_pie_quantity' => $this->actual_pie_quantity,
            'order_amount' => $this->order_amount,
            'replace_amount' => $this->replace_amount,
            'remark' => $this->remark,
            'batchs'    => BatchResource::collection($this->batchs)->sortBy('sort_id'),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
