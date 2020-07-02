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
            'list_mode' => $this->list_mode,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'express_first_no' => $this->express_first_no,
            'express_second_no' => $this->express_second_no,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'execution_date' => $this->execution_date,
            'batch_no' => $this->batch_no,
            'tour_no' => $this->tour_no,
            'line_id' => $this->line_id,
            'line_name' => $this->line_name,
            'out_order_no' => $this->out_order_no,
            'exception_label' => $this->exception_label,
            'exception_label_name' => $this->exception_label_name,
            'receiver_post_code' => $this->receiver_post_code,
            'exception_stage_name' => $this->exception_stage_name ?? '',
            'receiver_house_number' => $this->receiver_house_number,
            'driver_name' => $this->driver_name,
            'driver_phone' => $this->driver_phone,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
