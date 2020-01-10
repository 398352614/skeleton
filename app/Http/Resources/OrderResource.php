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
            'order_no' => $this->order_no,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'express_first_no' => $this->express_first_no,
            'express_second_no' => $this->express_second_no,
            'status_name' => $this->status_name,
            'execution_date' => $this->execution_date,
            'batch_no' => $this->batch_no,
            'tour_no' => $this->tour_no,
            'out_order_no' => $this->out_order_no,
            'source' => $this->source,
            'exception_label' => $this->exception_label,
            'exception_label_name' => $this->exception_label_name,
            'receiver_post_code' => $this->receiver_post_code,
            'exception_stage_name' => $this->exception_stage_name ?? '',
            'receiver_house_number' => $this->receiver_house_number,
            'driver_name' => $this->driver_name,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
