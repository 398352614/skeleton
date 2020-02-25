<?php


namespace App\Imports;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class OrderImport implements ToModel,WithHeadingRow
{
    public function Array(Array $tables)
    {
        return $tables;
    }

    /**
     * @param array $row
     *
     * @return Model|Model[]|null
     */
    public function model(array $row)
    {
        return new Order([
            'company_id'=>auth()->user()->company_id,
            'execution_date' => $row['execution_date'],
            'out_order_no' => $row['out_order_no'],
            'express_first_no' => $row['express_first_no'],
            'express_second_no' => $row['express_second_no'],
            'source' => $row['source'],
            'type' => $row['type'],
            'out_user_id' => $row['out_user_id'],
            'nature' => $row['nature'],
            'settlement_type' => $row['settlement_type'],
            'settlement_amount' => $row['settlement_amount'],
            'replace_amount' => $row['replace_amount'],
            'delivery' => $row['delivery'],
            'sender' => $row['sender'],
            'sender_phone' => $row['sender_phone'],
            'sender_country' => $row['sender_country'],
            'sender_post_code' => $row['sender_post_code'],
            'sender_house_number' => $row['sender_house_number'],
            'sender_city' => $row['sender_city'],
            'sender_street' => $row['sender_street'],
            'sender_address' => $row['sender_address'],
            'receiver' => $row['receiver'],
            'receiver_phone' => $row['receiver_phone'],
            'receiver_country' => $row['receiver_country'],
            'receiver_post_code' => $row['receiver_post_code'],
            'receiver_house_number' => $row['receiver_house_number'],
            'receiver_city' => $row['receiver_city'],
            'receiver_street' => $row['receiver_street'],
            'receiver_address' => $row['receiver_address'],
            'special_remark' => $row['special_remark'],
            'remark' => $row['remark'],
            'item_list' => $row['item_list'],

        ]);
    }

}
