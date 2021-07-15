<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class StockExceptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'company_id' => $this->company_id,
            'stock_exception_no' => $this->stock_exception_no,
            'tracking_order_no' => $this->tracking_order_no,
            'express_first_no' => $this->express_first_no,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'remark' => __($this->remark),
            'status' => $this->status,
            'status_name' => $this->status_name,
            'deal_remark' => __($this->deal_remark),
            'deal_time' => $this->deal_time,
            'operator' => $this->operator,
            'operator_id' => $this->operator_id,
            'order_no'=>$this->order_no ?? '',
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
