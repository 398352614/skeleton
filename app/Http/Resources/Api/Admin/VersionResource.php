<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class VersionResource extends JsonResource
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
        'uploader_email' => $this->uploader_email,
        'name' => $this->name,
        'version'=>$this->version,
        'status'=>$this->status,
        'url'=>$this->url,
        'change_log'=>$this->change_log,
        'created_at' => (string)$this->created_at,
        'updated_at' => (string)$this->updated_at,
    ];
    }
}
