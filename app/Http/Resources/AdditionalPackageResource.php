<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AdditionalPackageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'company_id',
            'merchant_id',
            'batch_no',
            'package_no',
            'execution_date',
            'status',
            'receiver_fullname',
            'receiver_phone',
            'receiver_country',
            'receiver_post_code',
            'receiver_house_number',
            'receiver_city',
            'receiver_street',
            'receiver_address',
            'receiver_lon',
            'receiver_lat',
            'created_at',
            'updated_at',
        ];
    }
}
