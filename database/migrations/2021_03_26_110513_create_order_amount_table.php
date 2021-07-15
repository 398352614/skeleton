<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderAmountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_amount', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->default(0)->nullable()->comment('公司ID');
            $table->string('order_no',50)->default('')->nullable()->comment('订单号');
            $table->decimal('expect_amount', 16, 2)->default(0.00)->nullable()->comment('预计金额');
            $table->decimal('actual_amount', 16, 2)->default(0.00)->nullable()->comment('实际金额');
            $table->tinyInteger('type')->default(8)->nullable()->comment('运费类型');
            $table->string('remark')->default('')->nullable()->comment('备注');
            $table->tinyInteger('in_total')->default(1)->nullable()->comment('计入总费用1-计入2-不计入');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-预产生2-已产生3-已支付4-已取消');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_amount');
    }
}
