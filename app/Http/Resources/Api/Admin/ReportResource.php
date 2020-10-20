<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tour_no' => $this->tour_no,
            'line_name' => $this->line_name,
            'execution_date' => $this->execution_date,
            'driver_name' => $this->driver_name,
            'expect_pickup_quantity' => $this->expect_pickup_quantity,
            'actual_pickup_quantity' => $this->actual_pickup_quantity,
            'expect_pie_quantity' => $this->expect_pie_quantity,
            'actual_pie_quantity' => $this->actual_pie_quantity,
            'expect_batch_count' => $this->expect_batch_count ?? 0,
            'actual_batch_count' => $this->actual_batch_count ?? 0,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
