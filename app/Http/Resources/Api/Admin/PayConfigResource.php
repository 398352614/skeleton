<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class PayConfigResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'waiting_time' => $this->waiting_time,
            'paypal_sandbox_mode' => $this->paypal_sandbox_mode,
            'paypal_client_id' => $this->paypal_client_id,
            'paypal_client_secret' => $this->paypal_client_secret,
            'paypal_status' => $this->paypal_status,
            'paypal_status_name' => $this->paypal_status_name,
            'paypal_sandbox_mode_name' => $this->paypal_sandbox_mode_name,

            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
