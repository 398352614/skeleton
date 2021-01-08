<?php

namespace App\Http\Resources\api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotifyPackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'express_first_no' => $this->express_first_no,
            'express_second_no' => $this->express_second_no,
            'out_order_no' => $this->out_order_no,
            'expect_quantity' => $this->expect_quantity,
            'actual_quantity' => $this->actual_quantity,
            'sticker_no' => $this->sticker_no,
            'sticker_amount' => $this->sticker_amount,
            'delivery_amount' => $this->name,
            'delivery_count' => $this->express_first_no,
            'is_auth' => $this->express_second_no,
            'auth_fullname' => $this->out_order_no,
            'auth_birth_date' => $this->expect_quantity,
            'status' => $this->actual_quantity,
            'type' => $this->sticker_no
        ];
    }
}
