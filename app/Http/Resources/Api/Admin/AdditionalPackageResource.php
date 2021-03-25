<?php


namespace App\Http\Resources\Api\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

class AdditionalPackageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'company_id'=>$this->company_id,
            'merchant_id'=>$this->merchant_id,
            'merchant_name'=>$this->merchant_name,
            'batch_no'=>$this->batch_no,
            'package_no'=>$this->package_no,
            'execution_date'=>$this->execution_date,
            'sticker_no'=>$this->sticker_no,
            'sticker_amount'=>$this->sticker_amount,
            'delivery_amount'=>$this->delivery_amount,
            'status'=>$this->status,
            'place_fullname'=>$this->place_fullname,
            'place_phone'=>$this->place_phone,
            'place_country'=>$this->place_country,
            'place_province'=>$this->place_province,
            'place_post_code'=>$this->place_post_code,
            'place_house_number'=>$this->place_house_number,
            'place_city'=>$this->place_city,
            'place_district'=>$this->place_district,
            'place_street'=>$this->place_street,
            'place_address'=>$this->place_address,
            'place_lon'=>$this->place_lon,
            'place_lat'=>$this->place_lat,
            'created_at'=>(string)$this->created_at,
            'updated_at'=>(string)$this->updated_at,
        ];
    }
}
