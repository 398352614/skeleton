<?php

namespace App\Http\Resources\Api\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderImportResource extends JsonResource
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
        'id' => $this->id,
        'company_id' => $this->company_id,
        'url' => $this->url,
        'status' => $this->status,
        'success_order'=>$this->success_order,
        'fail_order'=>$this->fail_order,
        'created_at' => (string)$this->created_at,
        'updated_at' => (string)$this->updated_at,
    ];
    }
}
