<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRechargeAddColumnsTourNoExecutionDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recharge', function (Blueprint $table) {
            $table->string('tour_no')->default('')->nullable()->after('recharge_no')->comment('取件线路编号');
            $table->dateTime('execution_date')->default(null)->nullable()->after('tour_no')->comment('取派日期');
        });
        Schema::table('recharge_statistics', function (Blueprint $table) {
            $table->string('tour_no')->default('')->nullable()->after('merchant_id')->comment('取件线路编号');
            $table->dateTime('execution_date')->default(null)->nullable()->after('tour_no')->comment('取派日期');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recharge', function (Blueprint $table) {
            $table->dropColumn('tour_no');
            $table->dropColumn('execution_date');
        });
        Schema::table('recharge_statistics', function (Blueprint $table) {
            $table->dropColumn('tour_no');
            $table->dropColumn('execution_date');
        });
    }
}
