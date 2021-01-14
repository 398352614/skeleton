<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleEmployeeListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'fullname' => $this->fullname,
        ];
    }
}
