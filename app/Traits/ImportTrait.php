<?php
/**
 * 导入类
 */

namespace App\Traits;


use App\Exceptions\BusinessLogicException;
use App\Imports\LineImport;
use App\Imports\OrderImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\HeadingRowImport;

Trait ImportTrait
{
    public function orderExcelImport($path){
        return Excel::toArray(new OrderImport, $path);
    }

    public function lineExcelImport($path){
        return Excel::toArray(new lineImport, $path);
    }

}
