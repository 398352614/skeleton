<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceAgreementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'tittle' => $this->tittle,
            'text' => $this->text,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'operator_name' => $this->operator_name,
            'operator_id' => $this->operator_id,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
