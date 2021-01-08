<?php

namespace App\Http\Resources\api;

use App\Http\Resources\api\MerchantApi\NotifyMaterialResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotifyInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge($this->baseToArray(), $this->specialToArray());
    }

    public function baseToArray()
    {
        $newFields = [];
        $fields = [
            'tour_no',
            'batch_no',
            'order_no',
            'order_status',
            'order_type',
            'out_order_no',
            'tracking_order_no',
            'status',
            'type',
            'merchant_id',
            'line_id',
            'line_name',
            'execution_date',
            'driver_id',
            'driver_name',
            'driver_phone',
            'car_id',
            'car_no',
            'expect_arrive_time',
            'expect_distance',
            'expect_time',
            'place_fullname',
            'place_phone',
            'place_country',
            'place_post_code',
            'place_house_number',
            'place_city',
            'place_street',
            'place_address',
            'pay_type',
            'replace_amount',
            'settlement_amount',
            'package_list',
            'material_list'
        ];
        foreach ($fields as $k => $v) {
            $newFields[$v] = $this->$v ?? '';
        }
        return $newFields;
    }

    public function specialToArray()
    {
        return [
            'package_list' => NotifyInfoResource::make($this->package_list),
            'material_list' => NotifyMaterialResource::make($this->material_list),
            'replace_amount' => $this->replace_amount ?? 0,
            'settlement_amount' => $this->settlement_amount ?? 0,
        ];
    }
}
