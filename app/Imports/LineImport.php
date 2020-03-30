<?php


namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class LineImport implements ToArray
{
    public function Array(Array $tables)
    {
        return $tables;
    }
}
