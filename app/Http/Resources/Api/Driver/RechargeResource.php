<?php


namespace App\Http\Resources\Api\Driver;


use Illuminate\Http\Resources\Json\JsonResource;

class RechargeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'merchant_id' => $this->merchant_id,
            'recharge_statistics_id' => $this->recharge_statistics_id,
            'merchant_name' => $this->merchant_name,
            'recharge_no' => $this->recharge_no,
            'transaction_number' => $this->transaction_number,
            'driver_name' => $this->driver_name,
            'out_user_id' => $this->out_user_id,
            'out_user_name' => $this->out_user_name,
            'recharge_date' => $this->recharge_date,
            'recharge_time' => $this->recharge_time,
            'recharge_amount' => $this->recharge_amount,
            'remark' => $this->remark,
            'status' => $this->status,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'status_name'=>$this->status_name,
        ];
    }
}
