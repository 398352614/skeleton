<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id ?? '',
            'email' => $this->email,
            'fullname' => $this->fullname,
            'username' => $this->username,
            'phone' => $this->phone ?? '',
            'remark' => $this->country ?? '',
            'group' => $this->group ?? '',
            'institution' => EmployeeInstitutionListResource::make($this->institution),
            'forbid_login' => $this->forbid_login,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
