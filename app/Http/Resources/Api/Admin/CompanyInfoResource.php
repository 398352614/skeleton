<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id ?? '',
            'name' => $this->name ?? '',
            'contacts' => $this->contacts ?? '',
            'phone' => $this->phone ?? '',
            'country' => $this->country ?? '',
            'country_name' => $this->country_name ?? '',
            'address' => $this->address ?? '',
        ];
    }
}
