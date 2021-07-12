<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //删除tile_adhesive_dosage表中所有数据
        DB::table('address_template')->whereRaw('1')->delete();
        //将自增ID初始值设置为1
        DB::raw("alter table address_template AUTO_INCREMENT=1;");
        $now = now();
        //地址模板1
        $address1 = [
            'fullname' => 'required|string|max:50',
            'phone' => 'required|string|max:20|regex:/^[0-9]([0-9- ])*[0-9]$/',
            'post_code' => 'required|string|max:50',
            'house_number' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'street' => 'required|string|max:50',
            'alternate_address' => 'nullable|string|max:250',
        ];
        //地址模板2
        $address2 = [
            'fullname' => 'required|string|max:50',
            'phone' => 'required|string|max:20|regex:/^[0-9]([0-9- ])*[0-9]$/',
            'address' => 'required|string|max:250',
            'alternate_address' => 'nullable|string|max:250',
        ];
        DB::table('address_template')->insert([
            ['template' => json_encode($address1, JSON_UNESCAPED_UNICODE), 'created_at' => $now, 'updated_at' => $now],
            ['template' => json_encode($address2, JSON_UNESCAPED_UNICODE), 'created_at' => $now, 'updated_at' => $now]
        ]);
        \Illuminate\Support\Facades\Artisan::call('cache:address-template');
    }
}
