<?php

namespace App\Http\Resources\Api\Driver;

use App\Services\GisService;
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
        ], GisService::corTransfer(['lon'=>$this->lon,'lat'=>$this->lat]));
    }
}
