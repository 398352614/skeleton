<?php
/**
 * 司机端 - 取件线路中的站点列表
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TourBatchResource extends JsonResource
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
            'car_id' => $this->car_id,
            'car_no' => $this->car_no,
            'execution_date' => $this->execution_date,
            'expect_pickup_quantity' => $this->expect_pickup_quantity,
            'actual_pickup_quantity' => $this->actual_pickup_quantity,
            'expect_pie_quantity' => $this->expect_pie_quantity,
            'actual_pie_quantity' => $this->actual_pie_quantity,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'batch_count' => $this->batch_count,
            'actual_batch_count' => $this->actual_batch_count,
            'batch_list' => $this->batch_list
        ];
    }
}
