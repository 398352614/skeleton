<?php

namespace App\Http\Resources\Api\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class TourTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tour_no' => $this->tour_no,
            'car_id' => $this->car_id,
            'car_no' => $this->car_no,
            'execution_date' => $this->execution_date,
            'expect_pickup_quantity' => $this->expect_pickup_quantity,
            'actual_pickup_quantity' => $this->actual_pickup_quantity,
            'expect_pie_quantity' => $this->expect_pie_quantity,
            'actual_pie_quantity' => $this->actual_pie_quantity,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'batch_count' => $this->batch_count,
            'last_place' => $this->last_place,
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
            'is_exist_special_remark' => $this->is_exist_special_remark,
            'actual_out_status' => $this->actual_out_status,
            'end_time' => $this->end_time
        ];
    }
}
