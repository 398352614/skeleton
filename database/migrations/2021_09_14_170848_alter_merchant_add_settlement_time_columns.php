<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMerchantAddSettlementTimeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant', function (Blueprint $table) {
            $table->tinyInteger('auto_settlement')->default(null)->nullable()->comment('是否自动结算1-是2=否')->after('settlement_type');
            $table->string('settlement_time')->default(null)->nullable()->comment('每日结算时间')->after('auto_settlement');
            $table->integer('settlement_week')->default(null)->nullable()->comment('每周结算星期')->after('settlement_time');
            $table->string('settlement_date')->default(null)->nullable()->comment('每月结算日期')->after('settlement_week');
            $table->dateTime('last_settlement_date')->default(null)->nullable()->comment('上次结算日期')->after('settlement_date');
            $table->index('status');
        });
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
