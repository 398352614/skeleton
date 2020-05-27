<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/4/1
 * Time: 17:02
 */

namespace App\Services\Admin;


use App\Models\TourDriverEvent;
use App\Services\BaseService;

class TourDriverService extends BaseService
{
    public function __construct(TourDriverEvent $tourDriverEvent)
    {
        parent::__construct($tourDriverEvent);
    }

    public function getListByTourNo($tourNo)
    {
        $list = parent::getList(['tour_no' => $tourNo], ['*'], false);
        return $list;
    }
}
