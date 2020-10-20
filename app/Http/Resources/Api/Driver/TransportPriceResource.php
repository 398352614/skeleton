<?php
/**
 * 运价管理
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:42
 */

namespace App\Http\Resources\Api\Driver;

use App\Traits\ConstTranslateTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class TransportPriceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'starting_price' => $this->starting_price,
            'remark' => $this->remark,
            'status' => $this->status,
            'km_list' => $this->km_list,
            'weight_list' => $this->weight_list,
            'special_time_list' => $this->special_time_list,
            'created_at' => (string)$this->created_at,
        ];
    }

}
