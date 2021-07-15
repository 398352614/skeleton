<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTourAlterColumnWareHouseExpectArriveTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->integer('warehouse_expect_time')->nullable()->default(0)->after('warehouse_lat')->comment('抵达网点预计耗时')->change();
            $table->integer('warehouse_expect_distance')->nullable()->default(0)->after('warehouse_expect_time')->comment('抵达网点预计历程')->change();
            $table->dateTime('warehouse_expect_arrive_time')->nullable()->default(null)->after('warehouse_expect_distance')->comment('抵达网点预计时间')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
