<?php

namespace App\Http\Resources;

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
            'id'                            =>$this->id,
            'email'                         => $this->email,
            'last_name'                     => $this->last_name,
            'first_name'                    => $this->first_name,
            'gender'                        => $this->gender,
            'birthday'                      => $this->birthday,
            'phone'                         => $this->phone,
            'duty_paragraph'                => $this->duty_paragraph,
            'post_code'                     => $this->post_code,
            'door_no'                       => $this->door_no,
            'street'                        => $this->street,
            'city'                          => $this->city,
            'country'                       => $this->country,
            'lisence_number'                => $this->lisence_number,
            'lisence_valid_date'            => $this->lisence_valid_date,
            'lisence_type'                  => $this->lisence_type,
            'lisence_material'              => $this->lisence_material,
            'government_material'           => $this->government_material,
            'avatar'                        => $this->avatar,
            'bank_name'                     => $this->bank_name,
            'iban'                          => $this->iban,
            'bic'                           => $this->bic,
            'crop_type'                     => $this->crop_type,
            'is_locked'                     => $this->is_locked,
            'is_locked_name'                =>$this->is_locked_name,
            'created_at'                    =>(string)$this->created_at
        ];
    }
}
