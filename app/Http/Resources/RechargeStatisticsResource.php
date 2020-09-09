<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class RechargeStatisticsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'merchant_id' => $this->merchant_id,
            'merchant_name' => $this->merchant_name,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'total_recharge_amount'=>$this->total_recharge_amount,
            'status' => $this->status,
            'verify_recharge_amount' => $this->verify_recharge_amount,
            'verify_date' => $this->verify_date,
            'verify_time' => $this->verify_time,
            'verify_remark' => $this->verify_remark,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'status_name'=>$this->status_name,
        ];
    }
}
