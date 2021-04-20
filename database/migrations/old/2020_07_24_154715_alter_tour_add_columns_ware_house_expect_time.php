<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTourAddColumnsWareHouseExpectTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->integer('warehouse_expect_time')->default(0)->after('warehouse_lat')->comment('抵达网点预计耗时');
            $table->integer('warehouse_expect_distance')->default(0)->after('warehouse_expect_time')->comment('抵达网点预计历程');
            $table->dateTime('warehouse_expect_arrive_time')->default(null)->after('warehouse_expect_distance')->comment('抵达网点预计时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->dropColumn('warehouse_expect_time');
            $table->dropColumn('warehouse_expect_distance');
            $table->dropColumn('warehouse_expect_arrive_time');
        });
    }
}
