<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterAllTableAlterColumnsDefaultNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            // 开启事物
            DB::beginTransaction();
            $path =  __DIR__ . DIRECTORY_SEPARATOR . 'alter.sql';
            if (file_exists($path)) {
                DB::unprepared(file_get_contents($path));
            }
            // 提交事物
            DB::commit();
        } catch (\Exception $e) {
            // 回滚事物
            DB::rollBack();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
