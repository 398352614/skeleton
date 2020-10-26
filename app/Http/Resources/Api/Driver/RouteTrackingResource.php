<?php

namespace App\Http\Resources\Api\Driver;

use App\Services\CorTransferService;
use App\Traits\CompanyTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteTrackingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge([
            'lon' => $this->lon,
            'lat' => $this->lat,
            'tour_no' => $this->tour_no,
            'driver_id' => $this->driver_id,
            'tour_driver_event_id' => $this->tour_driver_event_id,
            'time' => $this->time,
        ], $this->corTransfer());
    }

    public function corTransfer()
    {
        if (empty($this->lat) || empty($this->lon)) {
            return ['lat' => $this->lat, 'lon' => $this->lon,];
        }
        if ((CompanyTrait::getCompany()['map'] == 'baidu')) {
            $cor = CorTransferService::tenCentToBaiDu($this->lat, $this->lon);
            $cor = array_values($cor);
        } else {
            $cor = [$this->lat, $this->lon];
        }
        return [
            'lat' => $cor[0],
            'lon' => $cor[1],
        ];
    }
}
