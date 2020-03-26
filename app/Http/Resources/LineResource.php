<?php
/**
 * 线路列表
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:42
 */

namespace App\Http\Resources;

use App\Traits\ConstTranslateTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class LineResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'country' => $this->country,
            'country_name' => $this->country_name,
            'line_range' => $this->line_range,
            'work_day_list' => $this->getWorkDayList($this->work_day_list),
            'pickup_max_count' => $this->pickup_max_count,
            'pie_max_count'=>$this->pie_max_count,
            'is_increment'=>$this->is_increment,
            'order_deadline'=>$this->order_deadline,
            'creator_name' => $this->creator_name,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }


    public function getWorkDayList($workDayList)
    {
        $week = [];
        foreach ($workDayList as $workDay) {
            $week[$workDay] = ConstTranslateTrait::weekList($workDay);
        }
        return implode(',', $week);
    }
}
