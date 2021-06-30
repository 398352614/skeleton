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
            $table->string('tour_no')->default('')->nullable()->after('recharge_no')->comment('线路任务编号');
            $table->date('execution_date')->default(null)->nullable()->after('tour_no')->comment('取派日期');
            $table->string('line_id')->default('')->nullable()->after('execution_date')->comment('线路ID');
            $table->string('line_name')->default('')->nullable()->after('line_id')->comment('线路名');

        });
        Schema::table('recharge_statistics', function (Blueprint $table) {
            $table->string('tour_no')->default('')->nullable()->after('merchant_id')->comment('线路任务编号');
            $table->date('execution_date')->default(null)->nullable()->after('tour_no')->comment('取派日期');
            $table->string('line_id')->default('')->nullable()->after('execution_date')->comment('线路ID');
            $table->string('line_name')->default('')->nullable()->after('line_id')->comment('线路名');
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
            $table->dropColumn('line_id');
            $table->dropColumn('line_name');
        });
        Schema::table('recharge_statistics', function (Blueprint $table) {
            $table->dropColumn('tour_no');
            $table->dropColumn('execution_date');
            $table->dropColumn('line_id');
            $table->dropColumn('line_name');
        });
    }
}
