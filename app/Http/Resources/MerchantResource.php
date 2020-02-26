<?php
/**
 * 商户列表
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:42
 */

namespace App\Http\Resources;

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
            'name' => $this->name,
            'email' => $this->email,
            'settlement_type' => $this->settlement_type,
            'merchant_group_id' => $this->merchant_group_id,
            'merchant_group_name' => MerchantGroup::query()->where('id',$this->merchant_group_id)->value('name'),
            'contacter' => $this->contacter,
            'phone' => $this->phone,
            'address' => $this->address,
            'avatar' => $this->avatar,
            'status' => $this->status,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }

}
