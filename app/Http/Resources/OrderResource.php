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
            'status_name' => $this->status_name,
            'exception_type_name' => $this->exception_type_name,
            'execution_date' => $this->execution_date,
            'batch_no' => $this->batch_no,
            'tour_no' => $this->tour_no,
            'out_order_no' => $this->out_order_no,
            'source' => $this->source,
            'receiver_post_code' => $this->receiver_post_code,
            'receiver_house_number' => $this->receiver_house_number,
            'driver_name' => $this->driver_name,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}