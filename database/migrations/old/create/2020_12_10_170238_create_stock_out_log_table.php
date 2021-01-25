<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockOutLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_out_log', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('line_id')->default(null)->nullable()->comment('线路ID');
            $table->string('line_name')->default('')->nullable()->comment('线路名称');
            $table->string('order_no')->default('')->nullable()->comment('订单号');
            $table->date('execution_date')->default(null)->nullable()->comment('预计出库日期');
            $table->string('tracking_order_no')->default('')->nullable()->comment('运单号');
            $table->string('express_first_no')->default('')->nullable()->comment('包裹单号');
            $table->decimal('weight')->default(0)->nullable()->comment('重量');
            $table->string('operator')->default('')->nullable()->comment('操作人');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->index('company_id', 'company_id');
            $table->index('order_no', 'order_no');
            $table->index('execution_date', 'execution_date');
            $table->index('tracking_order_no', 'tracking_order_no');
            $table->index('express_first_no', 'express_first_no');
            $table->index('line_id', 'line_id');
            $table->index('express_first_no', 'express_first_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_out_log');
    }
}
