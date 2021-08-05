<?php


namespace App\Manager\ExcelImport;

use Maatwebsite\Excel\Concerns\ToArray;


class AddressImport implements ToArray
{

    public function array(array $array)
    {
        return $array;
    }
}
