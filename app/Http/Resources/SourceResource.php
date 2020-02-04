<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class SourceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'source_name' => $this->source_name,
        ];
    }
}
