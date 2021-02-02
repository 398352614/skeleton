<?php
/**
 * 订单列表
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:42
 */

namespace App\Http\Resources\Api\Merchant;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'merchant_id' => $this->merchant_id,
            'merchant_id_name' => $this->merchant_id_name,
            'order_no' => $this->order_no,
            'execution_date' => $this->execution_date,
            'batch_no' => $this->batch_no,
            'tour_no' => $this->tour_no,
            'out_order_no' => $this->out_order_no,
            'express_first_no' => $this->express_first_no,
            'express_second_no' => $this->express_second_no,
            'mask_code' => $this->mask_code,
            'source' => $this->source,
            'source_name' => $this->source_name,
            'list_mode' => $this->list_mode,
            'type' => $this->type,
            'out_user_id' => $this->out_user_id,
            'nature' => $this->nature,
            'settlement_type' => $this->settlement_type,
            'settlement_amount' => $this->settlement_amount,
            'replace_amount' => $this->replace_amount,
            'delivery' => $this->delivery,
            'status' => $this->status,
            'second_place_fullname' => $this->second_place_fullname,
            'second_place_phone' => $this->second_place_phone,
            'second_place_country' => $this->second_place_country,
            'second_place_country_name' => $this->second_place_country_name,
            'second_place_post_code' => $this->second_place_post_code,
            'second_place_house_number' => $this->second_place_house_number,
            'second_place_city' => $this->second_place_city,
            'second_place_street' => $this->second_place_street,
            'second_place_address' => $this->second_place_address,
            'place_fullname' => $this->place_fullname,
            'place_phone' => $this->place_phone,
            'place_country' => $this->place_country,
            'place_country_name' => $this->place_country_name,
            'place_post_code' => $this->place_post_code,
            'place_house_number' => $this->place_house_number,
            'place_city' => $this->place_city,
            'place_street' => $this->place_street,
            'place_address' => $this->place_address,
            'special_remark' => $this->special_remark,
            'remark' => $this->remark,
            'unique_code' => $this->unique_code,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'driver_phone' => $this->driver_phone,
            'starting_price' => $this->starting_price,
            'transport_price_type' => $this->transport_price_type,
            'transport_price_type_name' => $this->transport_price_type_name,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'item_list' => $this->item_list,
            'lon' => $this->lon,
            'lat' => $this->lat
        ];
    }
}
