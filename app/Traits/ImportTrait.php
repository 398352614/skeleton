<?php
/**
 * 导入类
 */

namespace App\Traits;


use App\Imports\AddressImport;
use App\Imports\LineImport;
use App\Imports\OrderImport;
use Maatwebsite\Excel\Facades\Excel;

Trait ImportTrait
{
    public function orderExcelImport($path){
        return Excel::toArray(new OrderImport, $path);
    }

    public function lineExcelImport($path){
        return Excel::toArray(new lineImport, $path);
    }

    public function addressExcelImport($path){
        return Excel::toArray(new AddressImport, $path);
    }
}
