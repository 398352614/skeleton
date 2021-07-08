<?php

namespace App\Http\Resources\Api\Merchant;

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
            'receiver' => $this->receiver,
            'source' => __($this->source),
            'created_at' => (string)$this->created_at,
            'driver_name' => $this->driver_name,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'remark' => $this->remark,
            'deal_name' => $this->deal_name,
            'deal_time' => $this->deal_time
        ];
    }
}
