<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeListResource extends JsonResource
{
    public function toArray($request)
    {
        if(empty($this->institution_id)){
            return [
                'id' => $this->id ?? '',
                'email' => $this->email,
                'fullname' => $this->fullname,
                'username' => $this->username,
                'phone' => $this->phone ?? '',
                'remark' => $this->remark ?? '',
                'group' => $this->group ?? '',
                'institution' => '',
                'forbid_login' => $this->forbid_login,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ];
        }
        return [
            'id' => $this->id ?? '',
            'email' => $this->email,
            'fullname' => $this->fullname,
            'username' => $this->username,
            'phone' => $this->phone ?? '',
            'remark' => $this->remark ?? '',
            'group' => $this->group ?? '',
            'institution' => EmployeeInstitutionListResource::make($this->institution),
            'forbid_login' => $this->forbid_login,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
