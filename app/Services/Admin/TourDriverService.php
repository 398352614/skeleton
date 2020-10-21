<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/4/1
 * Time: 17:02
 */

namespace App\Services\Admin;


use App\Http\Resources\Api\Admin\Api\Admin\TourDriverEventResource;
use App\Models\Batch;
use App\Models\TourDriverEvent;
use App\Services\BaseService;

class TourDriverService extends BaseService
{
    public function __construct(TourDriverEvent $tourDriverEvent)
    {
        parent::__construct($tourDriverEvent, TourDriverEventResource::class, TourDriverEventResource::class);
    }


    public function getListByTourNo($tourNo)
    {
        $list = parent::getList(['tour_no' => $tourNo], ['*'], false);
        foreach ($list as $k => $v) {
            if (!empty($v['batch_no'])) {
                $batch[$k] = Batch::query()->where('batch_no', $v['batch_no'])->first();
                if (!empty($batch[$k])) {
                    $list[$k]['expect_arrive_time'] = $batch[$k]['expect_arrive_time'];
                    $list[$k]['actual_arrive_time'] = $batch[$k]['actual_arrive_time'];
                    $list[$k]['expect_distance'] = $batch[$k]['expect_distance'];
                    $list[$k]['actual_distance'] = $batch[$k]['actual_distance'];
                } else {
                    $list[$k]['expect_arrive_time'] = '';
                    $list[$k]['actual_arrive_time'] = '';
                    $list[$k]['expect_distance'] = '';
                    $list[$k]['actual_distance'] = '';
                }

            } else {
                $list[$k]['expect_arrive_time'] = '';
                $list[$k]['actual_arrive_time'] = '';
                $list[$k]['expect_distance'] = '';
                $list[$k]['actual_distance'] = '';
            }
        }
        return $list;
    }
}
