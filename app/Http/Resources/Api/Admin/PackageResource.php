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

class PackageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'merchant_id' => $this->merchant_id,
            'batch_no' => $this->batch_no,
            'tour_no' => $this->tour_no,
            'order_no' => $this->order_no,
            'execution_date' => $this->execution_date,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'name' => $this->name,
            'out_order_no' => $this->out_order_no,
            'weight' => $this->weight,
            'expect_quantity' => $this->expect_quantity,
            'actual_quantity' => $this->actual_quantity,
            'sticker_no' => $this->sticker_no,
            'sticker_amount' => $this->sticker_amount,
            'delivery_amount' => $this->delivery_amount,
            'remark' => $this->remark,
            'express_first_no' => $this->express_first_no,
            'express_second_no' => $this->express_second_no,
            'status' => $this->status,
            'status_name' => $this->true_status_name,
            'stage' => $this->stage,
            'stage_name' => $this->stage_name,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'expiration_date' => $this->expiration_date,
            'second_execution_date' => $this->second_execution_date,
            'stock_in_time' => $this->stock_in_time,
            'warehouse_id' => $this->warehouse_id,
            'next_warehouse_id' => $this->next_warehouse_id,
            'warehouse_name' => $this->warehouse_name,
            'next_warehouse_name' => $this->next_warehouse_name,
            'shift_no' => $this->shift_no,
            'bag_no' => $this->bag_no,
            'stage_status_name' => $this->stage_status_name
        ];
    }
}
