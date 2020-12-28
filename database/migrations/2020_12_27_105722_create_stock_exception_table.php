<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockExceptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_exception', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('stock_exception_no',50)->default('')->nullable()->comment('入库异常编号');
            $table->string('tracking_order_no', 50)->default('')->nullable()->comment('运单号');
            $table->string('express_first_no', 50)->default('')->nullable()->comment('快递单号1');

            $table->integer('driver_id')->default(null)->nullable()->comment('司机ID');
            $table->string('driver_name', 50)->default('')->nullable()->comment('司机姓名');
            $table->string('remark', 250)->default('')->nullable()->comment('异常内容');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-未处理2-已处理');

            $table->string('deal_remark', 250)->default('')->nullable()->comment('处理内容');
            $table->dateTime('deal_time')->default(null)->nullable()->comment('处理时间');
            $table->string('operator', 250)->default('')->nullable()->comment('操作人');

            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique('stock_exception_no', 'stock_exception_no');
            $table->index('company_id', 'company_id');
            $table->index('tracking_order_no', 'tracking_order_no');
            $table->index('express_first_no', 'express_first_no');
            $table->index('driver_id', 'driver_id');
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
        Schema::dropIfExists('stock_exception');
    }
}
