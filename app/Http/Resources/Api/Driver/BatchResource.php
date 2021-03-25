<?php

namespace App\Http\Resources\Api\Driver;

use App\Services\GisService;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge([
            'id' => $this->id,
            'company_id' => $this->company_id,
            'merchant_id' => $this->merchant_id,
            'merchant_id_name' => $this->merchant_id_name,
            'batch_no' => $this->batch_no,
            'tour_no' => $this->tour_no,
            'line_id' => $this->line_id,
            'line_name' => $this->line_name,
            'execution_date' => $this->execution_date,
            'status' => $this->status,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'driver_phone' => $this->driver_phone,
            'driver_rest_time' => $this->driver_rest_time,
            'car_id' => $this->car_id,
            'car_no' => $this->car_no,
            'sort_id' => $this->sort_id,
            'is_skipped' => $this->is_skipped,
            'expect_pickup_quantity' => $this->expect_pickup_quantity,
            'actual_pickup_quantity' => $this->actual_pickup_quantity,
            'expect_pie_quantity' => $this->expect_pie_quantity,
            'actual_pie_quantity' => $this->actual_pie_quantity,
            'place_fullname' => $this->place_fullname,
            'place_phone' => $this->place_phone,
            'place_country' => $this->place_country,
            'place_country_name' => $this->place_country_name,
            'place_province' => $this->place_province,
            'place_post_code' => $this->place_post_code,
            'place_house_number' => $this->place_house_number,
            'place_city' => $this->place_city,
            'place_district' => $this->place_district,
            'place_street' => $this->place_street,
            'place_address' => $this->place_address,
            'expect_arrive_time' => $this->expect_arrive_time,
            'actual_arrive_time' => $this->actual_arrive_time,
            'sign_time' => $this->sign_time,
            'expect_distance' => $this->expect_distance,
            'actual_time' => $this->actual_time,
            'out_expect_arrive_time' => $this->out_expect_arrive_time,
            'out_expect_arrive_time_human' => $this->out_expect_arrive_time_human,
            'out_expect_distance' => $this->out_expect_distance,
            'out_actual_time' => $this->out_actual_time,
            'sticker_amount' => $this->sticker_amount,
            'delivery_amount' => $this->delivery_amount,
            'replace_amount' => $this->replace_amount,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'status_name' => $this->status_name,
            'additional_package_list' => $this->additional_package_list ?? [],
            'additional_package_count' => $this->additional_package_count ?? 0,
            'exception_label_name' => $this->exception_label_name,
            'pay_type_name' => $this->pay_type_name,
        ], GisService::corTransfer(['place_lon'=>$this->place_lon,'place_lat'=>$this->place_lat]));
    }
}
