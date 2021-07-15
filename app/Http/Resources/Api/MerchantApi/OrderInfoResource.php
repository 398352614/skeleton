<?php

namespace App\Http\Resources\Api\MerchantApi;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'order_no' => $this->order_no,
            'out_order_no' => $this->out_order_no,
            'order_status' => $this->status,
            'package_list' => PackageResource::make($this->package_list)->toArray(request())
        ];
    }
}
