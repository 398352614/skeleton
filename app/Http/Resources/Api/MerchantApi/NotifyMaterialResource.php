<?php

namespace App\Http\Resources\api\MerchantApi;

use Illuminate\Http\Resources\Json\JsonResource;

class NotifyMaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'out_order_no' => $this->out_order_no,
            'expect_quantity' => $this->expect_quantity,
            'actual_quantity' => $this->actual_quantity
        ];
    }
}
