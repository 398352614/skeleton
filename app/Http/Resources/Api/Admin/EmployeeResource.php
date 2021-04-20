<?php

namespace App\Http\Resources\Api\Admin;

use App\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id ?? '',
            'email' => $this->email,
            'fullname' => $this->fullname,
            'username' => $this->username,
            'phone' => $this->phone ?? '',
            'remark' => $this->remark ?? '',
            'forbid_login' => $this->forbid_login,
            'role_id' => $this->role_id,
            'role_id_name' => $this->role_id_name,
            'is_admin' => $this->is_admin,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'warehouse_id'=>$this->warehouse_id
        ];
    }
}
