<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AdditionalPackageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'company_id'=>$this->company_id,
            'merchant_id'=>$this->merchant_id,
            'merchant_name'=>$this->merchant_name,
            'batch_no'=>$this->batch_no,
            'package_no'=>$this->package_no,
            'execution_date'=>$this->execution_date,
            'status'=>$this->status,
            'receiver_fullname'=>$this->receiver_fullname,
            'receiver_phone'=>$this->receiver_phone,
            'receiver_country'=>$this->receiver_country,
            'receiver_post_code'=>$this->receiver_post_code,
            'receiver_house_number'=>$this->receiver_house_number,
            'receiver_city'=>$this->receiver_city,
            'receiver_street'=>$this->receiver_street,
            'receiver_address'=>$this->receiver_address,
            'receiver_lon'=>$this->receiver_lon,
            'receiver_lat'=>$this->receiver_lat,
            'created_at'=>(string)$this->created_at,
            'updated_at'=>(string)$this->updated_at,
        ];
    }
}
