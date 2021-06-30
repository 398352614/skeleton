<?php
/**
 * 司机端 - 线路任务中的站点列表
 */

namespace App\Http\Resources\Api\Merchant;

use App\Services\GisService;
use Illuminate\Http\Resources\Json\JsonResource;

class TourDriverEventResource extends JsonResource
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
            'company_id' => $this->company_id,
            'lon' => $this->lon,
            'lat' => $this->lat,
            'type' => $this->type,
            'content' => $this->content,
            'address' => $this->address,
            'icon_id' => $this->icon_id,
            'icon_path' => $this->icon_path,
            'batch_no' => $this->batch_no,
            'tour_no' => $this->tour_no,
            'route_tracking_id' => $this->route_tracking_id,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ], GisService::corTransfer(['lon'=>$this->lon,'lat'=>$this->lat]));
    }
}
