<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
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
            'id'                            => $this->id,
            'email'                         => $this->email,
            'fullname'                      => $this->fullname,
            'gender'                        => $this->gender,
            'birthday'                      => $this->birthday,
            'phone'                         => $this->phone,
            'duty_paragraph'                => $this->duty_paragraph,
            'address'                       => $this->address,
            'country'                       => $this->country,
            'country_name'                  => $this->country_name,
            'lisence_number'                => $this->lisence_number,
            'lisence_valid_date'            => !empty($this->lisence_valid_date) ? (string)$this->lisence_valid_date : null,
            'lisence_type'                  => $this->lisence_type,
            'lisence_material'              => $this->lisence_material,
            'lisence_material_name'         => $this->lisence_material_name,
            'government_material'           => $this->government_material,
            'government_material_name'      => $this->government_material_name,
            'avatar'                        => $this->avatar,
            'bank_name'                     => $this->bank_name,
            'iban'                          => $this->iban,
            'bic'                           => $this->bic,
            'crop_type'                     => $this->crop_type,
            'is_locked'                     => $this->is_locked,
            'is_locked_name'                => $this->is_locked_name,
            'created_at'                    => (string)$this->created_at,
            'type'                          => $this->type,
            'type_name'                     => $this->resource->getType($this->type),
            'warehouse_id'                     => $this->warehouse_id,
            'warehouse_name'                     => $this->warehouse_name
        ];
    }
}
