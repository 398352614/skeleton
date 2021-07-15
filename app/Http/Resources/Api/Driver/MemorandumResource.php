<?php

namespace App\Http\Resources\Api\Driver;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class MemorandumResource extends JsonResource
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
            'content' => Str::limit($this->content, 250),
            'created_at' => (string)$this->created_at,
            'image_list' => $this->image_list
        ];
    }
}
