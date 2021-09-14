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
            $table->tinyInteger('auto_settlement')->default(1)->nullable()->comment('是否自动结算1-是2=否')->after('settlement_type');
            $table->tinyInteger('settlement_time')->default(1)->nullable()->comment('每日结算时间')->after('auto_settlement');
            $table->tinyInteger('settlement_week')->default(1)->nullable()->comment('每周结算星期')->after('settlement_time');
            $table->tinyInteger('settlement_date')->default(1)->nullable()->comment('每月结算日期')->after('settlement_week');
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
