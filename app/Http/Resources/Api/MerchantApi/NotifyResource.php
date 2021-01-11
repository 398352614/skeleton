<?php

namespace App\Http\Resources\api\MerchantApi;

use Illuminate\Http\Resources\Json\JsonResource;

class NotifyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'tracking_order_no',
            'status',
            'type',
            'order_no',
            'order_status',
            'order_type'
        ];
    }
}
