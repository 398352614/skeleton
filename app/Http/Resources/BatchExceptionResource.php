<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BatchExceptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'batch_no' => $this->batch_no,
            'batch_exception_no' => $this->batch_exception_no,
            'stage' => $this->stage,
            'stage_name' => $this->stage_name,
            'status' => $this->status,
            'status_name' => $this->status_name,
        ];
    }
}
