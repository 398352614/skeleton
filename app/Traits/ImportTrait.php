<?php
/**
 * 导入类
 */

namespace App\Traits;


use App\Exceptions\BusinessLogicException;
use App\Imports\OrderImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\HeadingRowImport;

Trait ImportTrait
{
    public function excelImport($path){
        return Excel::toArray(new OrderImport, $path);
    }

    /**
     * 检查表头
     * @param $path
     * @param $heading
     * @throws BusinessLogicException
     */
    public function headingCheck($path,$heading){
        $validate= (new HeadingRowImport())->toArray($path)[0][0];
        if($validate !== $heading){
            throw new BusinessLogicException('表格格式不正确，请使用正确的模板导入');
        }
    }

}
