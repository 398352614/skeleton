<?php

namespace App\Http\Resources\Api\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeInstitutionListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'contacts' => $this->contacts ?? '',
            'country' => $this->country ?? '',
            'address' => $this->address ?? '',
            'phone' => $this->phone ?? '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
