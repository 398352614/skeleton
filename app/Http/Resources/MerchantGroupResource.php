<?php
/**
 * 商户组列表
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:42
 */

namespace App\Http\Resources;

use App\Traits\ConstTranslateTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantGroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'transport_price_id' => $this->transport_price_id,
            'is_default' => $this->is_default,
            'created_at' => (string)$this->created_at,
        ];
    }

}
