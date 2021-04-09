<?php
/**
 * 订单列表
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:42
 */

namespace App\Http\Resources\Api\Admin;

use App\Services\BaseConstService;
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
            'second_execution_date' => $this->second_execution_date,
            'out_order_no' => $this->out_order_no,
            'mask_code' => $this->mask_code,
            'source' => $this->source,
            'source_name' => $this->source_name,
            'list_mode' => $this->list_mode,
            'transport_mode' => $this->transport_mode,
            'transport_mode_name'=>$this->transport_mode_name,
            'origin_type' => $this->origin_type,
            'control_mode'=> $this->control_mode,
            'control_mode_name'=> $this->control_mode_name,
            'origin_type_name' => $this->origin_type_name,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'out_user_id' => $this->out_user_id,
            'nature' => $this->nature,
            'settlement_type' => $this->settlement_type,
            'settlement_type_name' => $this->settlement_type_name,
            'settlement_amount' => $this->settlement_amount,
            'replace_amount' => $this->replace_amount,
            'delivery' => $this->delivery,
            'delivery_name' => ($this->delivery == BaseConstService::YES) ? __('是') : __('否'),
            'status' => $this->status,
            'sticker_amount' => $this->sticker_amount,
            'delivery_amount' => $this->delivery_amount,
            'status_name' => $this->status_name,
            'second_place_fullname' => $this->second_place_fullname,
            'second_place_phone' => $this->second_place_phone,
            'second_place_country' => $this->second_place_country,
            'second_place_country_name' => $this->second_place_country_name,
            'second_place_province' => $this->second_place_province,
            'second_place_post_code' => $this->second_place_post_code,
            'second_place_house_number' => $this->second_place_house_number,
            'second_place_city' => $this->second_place_city,
            'second_place_district' => $this->second_place_district,
            'second_place_street' => $this->second_place_street,
            'second_place_address' => $this->second_place_address,
            'second_place_lon' => $this->second_place_lon,
            'second_place_lat' => $this->second_place_lat,
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
            'special_remark' => $this->special_remark,
            'remark' => $this->remark,
            'starting_price' => $this->starting_price,
            'transport_price_type' => $this->transport_price_type,
            'transport_price_type_name' => $this->transport_price_type_name,
            'receipt_type' => $this->receipt_type,
            'receipt_type_name' => $this->receipt_type_name,
            'receipt_count' => $this->receipt_count,
            'create_date' => $this->create_date,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'item_list' => $this->item_list,
            'expect_total_amount'=>$this->expect_total_amount,
            'actual_total_amount'=>$this->actual_total_amount
        ];
    }
}
