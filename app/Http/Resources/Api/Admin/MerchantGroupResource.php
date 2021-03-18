<?php
/**
 * 货主组列表
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:42
 */

namespace App\Http\Resources\Api\Admin;

use App\Models\TransportPrice;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantGroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'count'=>$this->count,
            'transport_price_id' => $this->transport_price_id,
            'transport_price_name' => TransportPrice::query()->where('id',$this->transport_price_id)->value('name'),
            'is_default' => $this->is_default,
            'created_at' => (string)$this->created_at,
        ];
    }

}
