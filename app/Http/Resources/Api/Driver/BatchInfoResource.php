<?php

namespace App\Http\Resources\Api\Driver;

use App\Services\GisService;
use App\Traits\CompanyTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge([
            'id' => $this->id,
            'company_id' => $this->company_id,
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
            'receiver_fullname' => $this->receiver_fullname,
            'receiver_phone' => $this->receiver_phone,
            'receiver_country' => $this->receiver_country,
            'receiver_country_name' => $this->receiver_country_name,
            'receiver_post_code' => $this->receiver_post_code,
            'receiver_house_number' => $this->receiver_house_number,
            'receiver_city' => $this->receiver_city,
            'receiver_street' => $this->receiver_street,
            'receiver_address' => $this->receiver_address,
            'receiver_lon' => $this->receiver_lon,
            'receiver_lat' => $this->receiver_lat,
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
            'order_count' => $this->order_count ?? null,
            'status_name' => $this->status_name,
            'exception_label_name' => $this->exception_label_name,
            'pay_type_name' => $this->pay_type_name,
            'orders' => OrderResource::collection($this->orders),
        ], GisService::corTransfer(['receiver_lon'=>$this->receiver_lon,'receiver_lat'=>$this->receiver_lat]));
    }
}
