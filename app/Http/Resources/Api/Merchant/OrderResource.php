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
            'express_first_no' => $this->express_first_no,
            'express_second_no' => $this->express_second_no,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'out_status' => $this->out_status,
            'out_status_name' => $this->out_status_name,
            'execution_date' => $this->execution_date,
            'batch_no' => $this->batch_no,
            'tour_no' => $this->tour_no,
            'line_id' => $this->line_id,
            'line_name' => $this->line_name,
            'out_order_no' => $this->out_order_no,
            'out_group_order_no' => $this->out_group_order_no,
            'exception_label' => $this->exception_label,
            'exception_label_name' => $this->exception_label_name,
            'place_post_code' => $this->place_post_code,
            'exception_stage_name' => $this->exception_stage_name ?? '',
            'place_house_number' => $this->place_house_number,
            'driver_name' => $this->driver_name,
            'driver_phone' => $this->driver_phone,
            'starting_price'=>$this->starting_price,
            'transport_price_type'=>$this->transport_price_type,
            'transport_price_type_name'=>$this->transport_price_type_name,
            'receipt_type'=>$this->receipt_type,
            'receipt_count'=>$this->receipt_count,
            'create_date'=>$this->create_date,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
