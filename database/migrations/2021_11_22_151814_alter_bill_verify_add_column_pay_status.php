<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBillVerifyAddColumnPayStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill_verify', function (Blueprint $table) {
            $table->tinyInteger('pay_status')->default(1)->nullable()->comment('状态1-未支付2-已支付3-已取消');
            $table->tinyInteger('pay_mode')->default(1)->nullable()->comment('支付模式1-线下2-线上');
            $table->index('pay_status','pay_status');
            $table->index('pay_mode','pay_mode');
        });
        Schema::table('bill', function (Blueprint $table) {
            $table->tinyInteger('pay_mode')->default(1)->nullable()->comment('支付模式1-线下2-线上');
            $table->index('pay_mode','pay_mode');
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
