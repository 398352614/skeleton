<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/18
 * Time: 17:47
 */

namespace App\Services\Admin;


use App\Models\Order;
use App\Services\ExportService;
use Illuminate\Database\Eloquent\Model;

class Order1Service extends ExportService
{
    public $header = [

    ];

    public function __construct(Order $model, $resource = null, $infoResource = null)
    {
        parent::__construct($model, $resource, $infoResource);
    }


    public function getDataList()
    {
        $dataList = [];




        return $dataList;
    }


}