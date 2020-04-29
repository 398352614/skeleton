<?php
/**
 * 订单列表
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:42
 */

namespace App\Http\Resources;

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
            'sender' => $this->sender,
            'sender_phone' => $this->sender_phone,
            'sender_country' => $this->sender_country,
            'sender_country_name' => $this->sender_country_name,
            'sender_post_code' => $this->sender_post_code,
            'sender_house_number' => $this->sender_house_number,
            'sender_city' => $this->sender_city,
            'sender_street' => $this->sender_street,
            'sender_address' => $this->sender_address,
            'receiver' => $this->receiver,
            'receiver_phone' => $this->receiver_phone,
            'receiver_country' => $this->receiver_country,
            'receiver_country_name' => $this->receiver_country_name,
            'receiver_post_code' => $this->receiver_post_code,
            'receiver_house_number' => $this->receiver_house_number,
            'receiver_city' => $this->receiver_city,
            'receiver_street' => $this->receiver_street,
            'receiver_address' => $this->receiver_address,
            'special_remark' => $this->special_remark,
            'remark' => $this->remark,
            'unique_code' => $this->unique_code,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'driver_phone' => $this->driver_phone,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'item_list' => $this->item_list,
            'lon'=>$this->lon,
            'lat'=>$this->lat
        ];
    }
}
