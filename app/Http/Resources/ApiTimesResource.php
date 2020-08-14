<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiTimesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'company_id',
            'date',
            'directions_times',
            'actual_directions_times',
            'api_directions_times',
            'distance_times',
            'actual_distance_times',
            'api_distance_times',
            'created_at',
            'updated_at',
        ];
    }

}
