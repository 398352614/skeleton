<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMerchantApiAddColumnRechargeStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_api', function (Blueprint $table) {
            $table->tinyInteger('recharge_status')->default(2)->nullable()->after('status')->comment('充值通道1-开启2关闭');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_api', function (Blueprint $table) {
            $table->dropColumn('recharge_status');
        });
    }
}
