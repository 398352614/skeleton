<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge([
            'car_length_id' => $this->resource->getOriginal('car_length'),
            'car_model_type_id' => $this->resource->getOriginal('car_model_type')
        ], parent::toArray($request));
    }
}
