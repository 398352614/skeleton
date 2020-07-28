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
            'status_name' => $this->status_name,
            'execution_date' => $this->execution_date,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
