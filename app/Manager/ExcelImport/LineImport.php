<?php


namespace App\Manager\ExcelImport;

use Maatwebsite\Excel\Concerns\ToArray;


class LineImport implements ToArray
{
    public function Array(Array $tables)
    {
        return $tables;
    }
}
