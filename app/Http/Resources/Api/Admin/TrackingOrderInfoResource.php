<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackingOrderInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'merchant_id' => $this->merchant_id,
            'merchant_id_name' => $this->merchant_id_name,
            'out_user_id' => $this->out_user_id,
            'out_order_no' => $this->out_order_no,
            'order_no' => $this->order_no,
            'tracking_order_no' => $this->tracking_order_no,
            'batch_no' => $this->batch_no,
            'tour_no' => $this->tour_no,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'execution_date' => $this->execution_date,
            'warehouse_fullname' => $this->warehouse_fullname,
            'warehouse_phone' => $this->warehouse_phone,
            'warehouse_country' => $this->warehouse_country,
            'warehouse_country_name' => $this->warehouse_country_name,
            'warehouse_post_code' => $this->warehouse_post_code,
            'warehouse_house_number' => $this->warehouse_house_number,
            'warehouse_city' => $this->warehouse_city,
            'warehouse_street' => $this->warehouse_street,
            'warehouse_address' => $this->warehouse_address,
            'warehouse_lon' => $this->warehouse_lon,
            'warehouse_lat' => $this->warehouse_lat,
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
            'place_lon' => $this->place_lon,
            'place_lat' => $this->place_lat,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'driver_phone' => $this->driver_phone,
            'car_id' => $this->car_id,
            'car_no' => $this->car_no,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'out_status' => $this->out_status,
            'out_status_name' => $this->out_status_name,
            'exception_label' => $this->exception_label,
            'exception_label_name' => $this->exception_label_name,
            'cancel_type' => $this->cancel_type,
            'cancel_remark' => $this->cancel_remark,
            'cancel_picture' => $this->cancel_picture,
            'mask_code' => $this->mask_code,
            'special_remark' => $this->special_remark,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'material_list' => $this->material_list,
            'package_list' => $this->package_list
        ];
    }

}
