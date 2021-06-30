<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingOrderMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_order_material', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('merchant_id')->default(null)->nullable()->comment('货主ID');
            $table->string('tour_no', 50)->default('')->nullable()->comment('线路任务编号');
            $table->string('batch_no', 50)->default('')->nullable()->comment('站点编号');
            $table->string('tracking_order_no', 50)->default('')->nullable()->comment('运单号');
            $table->tinyInteger('type')->default(1)->nullable()->comment('运单类型1-取件2-派件');
            $table->string('order_no', 50)->default('')->nullable()->comment('订单号');
            $table->date('execution_date')->default(null)->nullable()->comment('取派日期');
            $table->string('name', 50)->default('')->nullable()->comment('材料名称');
            $table->string('code', 50)->default('')->nullable()->comment('材料标识');
            $table->string('out_order_no', 50)->default('')->nullable()->comment('外部标识');
            $table->integer('expect_quantity')->default(1)->nullable()->comment('预计数量');
            $table->integer('actual_quantity')->default(0)->nullable()->comment('实际数量');
            $table->string('remark')->default('')->nullable()->comment('备注');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique('tracking_order_no', 'tracking_order_no');
            $table->index('company_id', 'company_id');
            $table->index('merchant_id', 'merchant_id');
            $table->index('execution_date', 'execution_date');
            $table->index('order_no', 'order_no');
            $table->index('batch_no', 'batch_no');
            $table->index('tour_no', 'tour_no');
            $table->index('type', 'type');
            $table->index('code', 'code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracking_order_material');
    }
}
