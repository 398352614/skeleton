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

class MaterialResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'out_order_no' => $this->out_order_no,
            'expect_quantity' => $this->expect_quantity,
            'actual_quantity' => $this->actual_quantity,
            'remark' => $this->remark,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'type'=>$this->type,
            'type_name'=>$this->type_name,
            'pack_type'=>$this->pack_type,
            'pack_type_name'=>$this->pack_type_name
        ];
    }
}
