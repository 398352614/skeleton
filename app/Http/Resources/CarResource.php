<?php

namespace App\Http\Resources;

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
        $car = parent::toArray($request);
         $car['brand_name'] = $this->brand_name;
         $car['mode_name'] = $this->model_name;
        return $car;
    }
}
