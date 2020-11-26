<?php
/**
 * 订单列表
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:42
 */

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'merchant_id' => $this->merchant_id,
            'merchant_id_name' => $this->merchant_id_name,
            'order_no' => $this->order_no,
            'source' => $this->source,
            'source_name' => $this->source_name,
            'mask_code' => $this->mask_code,
            'list_mode' => $this->list_mode,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'out_user_id' => $this->out_user_id,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'out_status' => $this->out_status,
            'out_status_name' => $this->out_status_name,
            'execution_date' => $this->execution_date,
            'second_execution_date' => $this->second_execution_date,
            'out_order_no' => $this->out_order_no,
            'exception_label' => $this->exception_label,
            'exception_label_name' => $this->exception_label_name,
            'place_post_code' => $this->place_post_code,
            'place_house_number' => $this->place_house_number,
            'second_place_post_code'=>$this->second_place_post_code,
            'second_place_house_number'=>$this->second_place_house_number,
            'replace_amount' => $this->replace_amount,
            'sticker_amount' => $this->sticker_amount,
            'settlement_amount' => $this->settlement_amount,

            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
