<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderNoRuleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'prefix' => $this->prefix,
            'start_index' => $this->start_index,
            'int_length' => $this->int_length,
            'start_string_index' => $this->start_string_index,
            'string_length' => $this->string_length,
            'max_no' => $this->max_no,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
