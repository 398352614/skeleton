<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class FeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'amount' => $this->amount,
            'level' => $this->level,
            'level_name' => $this->level_name,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'is_valuable'=>$this->is_valuable,
            'payer'=>$this->payer,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
