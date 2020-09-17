<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBatchAddColumnsFirstExpectDistance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch', function (Blueprint $table) {
            $table->dateTime('out_expect_arrive_time')->default(null)->nullable()->after('actual_arrive_time')->comment('出库预计时间');
            $table->integer('out_expect_distance')->default(0)->nullable()->after('actual_distance')->comment('出库预计里程');
            $table->integer('out_expect_time')->default(0)->nullable()->after('actual_time')->comment('出库预计耗时');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch', function (Blueprint $table) {
            $table->dropColumn('out_expect_arrive_time');
            $table->dropColumn('out_expect_distance');
            $table->dropColumn('out_expect_time');
        });
    }
}
