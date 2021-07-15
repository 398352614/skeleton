<?php

namespace App\Http\Resources\Api\MerchantApi;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'express_first_no' => $this->express_first_no,
            'out_order_no' => $this->out_order_no,
            'stage' => $this->stage,
            'status' => $this->status
        ];
    }
}
