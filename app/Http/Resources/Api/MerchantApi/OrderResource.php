<?php

namespace App\Http\Resources\Api\MerchantApi;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
