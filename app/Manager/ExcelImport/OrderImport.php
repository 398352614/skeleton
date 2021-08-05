<?php


namespace App\Manager\ExcelImport;

use Maatwebsite\Excel\Concerns\ToArray;


class OrderImport implements ToArray
{

    public function array(array $array)
    {
        return $array;
    }
}
