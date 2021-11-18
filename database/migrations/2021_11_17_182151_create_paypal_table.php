<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaypalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('merchant_id')->default(null)->nullable()->comment('货主ID');
            $table->integer('merchant_name')->default(null)->nullable()->comment('货主名称');
            $table->string('payment_id')->default(null)->nullable()->comment('支付单号');
            $table->string('payer_id')->default(null)->nullable()->comment('付款方ID');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-已创建2-已支付3-支付失败');
            $table->decimal('amount')->default(0)->nullable()->comment('数额');
            $table->tinyInteger('currency_unit_type')->default(null)->nullable()->comment('货币单位');
            $table->string('verify_no')->default(null)->nullable()->comment('对账单号');
            $table->string('object_no')->default(null)->nullable()->comment('系统编号');
            $table->timestamps();
            $table->index('company_id', 'company_id');
            $table->index('object_no', 'object_no');
            $table->index('status', 'status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paypal');
    }
}
