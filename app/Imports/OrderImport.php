<?php


namespace App\Imports;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class OrderImport implements ToArray
{

    public function array(array $array)
    {
        return $array;
    }
}
