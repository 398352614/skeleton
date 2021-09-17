<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFeeAddColumns2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee', function (Blueprint $table) {
            $table->tinyInteger('pay_type')->default(1)->nullable()->comment('支付方式1-系统支付2-现场支付')->after('is_valuable');
            $table->tinyInteger('payer_type')->default(4)->nullable()->comment('付款方类型4-货主5-寄方6-收方')->after('pay_type');
            $table->tinyInteger('payee_type')->default(1)->nullable()->comment('收款方类型1-公司7-司机转公司')->after('payer_type');
            $table->tinyInteger('pay_timing')->default(1)->nullable()->comment('支付时机1-下单时2-取件时3-派件时')->after('payee_type');
            $table->tinyInteger('object_type')->default(1)->nullable()->comment('主体类型1-订单2-包裹')->after('code');

            $table->index('status');
            $table->dropColumn('payer');
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
