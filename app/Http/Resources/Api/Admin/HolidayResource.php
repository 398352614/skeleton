<?php

namespace App\Http\Resources\Api\Admin;

use App\Models\Merchant;
use Illuminate\Http\Resources\Json\JsonResource;

class HolidayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'status' => $this->status,
            'date_list' => implode(',', $this->holidayDate->pluck('date')->toArray()),
            'merchant_list' => $this->merchantHoliday->isEmpty() ? [] : $this->getMerchantList($this->merchantHoliday->pluck('merchant_id')),
            'created_at' => (string)$this->created_at,
        ];
    }

    public function getMerchantList($merchantIdList)
    {
        return Merchant::query()->whereIn('id', $merchantIdList)->get(['id', 'name'])->toArray();
    }

}
