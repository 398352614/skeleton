<?php
/**
 * 商户列表
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:42
 */

namespace App\Http\Resources\Api\Merchant;

use App\Models\MerchantGroup;
use App\Traits\ConstTranslateTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'name' => $this->name,
            'email' => $this->email,
            'country' => $this->country,
            'settlement_type' => $this->settlement_type,
            'settlement_type_name' => $this->settlement_type_name,
            'merchant_group_id' => $this->merchant_group_id,
            'merchant_group_name' => MerchantGroup::query()->where('id', $this->merchant_group_id)->value('name'),
            'contacter' => $this->contacter,
            'phone' => $this->phone,
            'address' => $this->address,
            'avatar' => $this->avatar,
            'status' => $this->status,
            'additional_status' => $this->additional_status,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }

}
