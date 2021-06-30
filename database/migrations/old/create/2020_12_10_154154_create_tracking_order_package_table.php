<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingOrderPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_order_package', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('merchant_id')->default(null)->nullable()->comment('货主ID');
            $table->string('tour_no', 50)->default('')->nullable()->comment('线路任务编号');
            $table->string('batch_no', 50)->default('')->nullable()->comment('站点编号');
            $table->string('tracking_order_no', 50)->default('')->nullable()->comment('运单编号');
            $table->string('order_no', 50)->default('')->nullable()->comment('订单号');
            $table->date('execution_date')->default(null)->nullable()->comment('取派日期');
            $table->tinyInteger('type')->default(1)->nullable()->comment('运单类型1-取件2-派件');
            $table->string('name', 50)->default('')->nullable()->comment('包裹名称');
            $table->string('express_first_no', 50)->default('')->nullable()->comment('快递单号1');
            $table->string('express_second_no', 50)->default('')->nullable()->comment('快递单号2');
            $table->string('feature_logo', 50)->default('')->nullable()->comment('特性');
            $table->string('out_order_no', 50)->default('')->nullable()->comment('外部标识');
            $table->decimal('weight', 8, 2)->default(0.00)->nullable()->comment('重量');
            $table->integer('expect_quantity')->default(1)->nullable()->comment('预计数量');
            $table->integer('actual_quantity')->default(0)->nullable()->comment('实际数量');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站');
            $table->string('sticker_no', 50)->default('')->nullable()->comment('贴单号');
            $table->decimal('sticker_amount', 8, 2)->default(0.00)->nullable()->comment('贴单费用');
            $table->decimal('delivery_amount', 8, 2)->default(0.00)->nullable()->comment('提货费用');
            $table->string('remark')->default('')->nullable()->comment('备注');
            $table->tinyInteger('is_auth')->default(2)->nullable()->comment('是否需要身份验证1-是2-否');
            $table->string('auth_fullname', 100)->default('')->nullable()->comment('身份人姓名');
            $table->date('auth_birth_date')->default(null)->nullable()->comment('身份人出身年月');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique('tracking_order_no', 'tracking_order_no');
            $table->index('company_id', 'company_id');
            $table->index('merchant_id', 'merchant_id');
            $table->index('execution_date', 'execution_date');
            $table->index('order_no', 'order_no');
            $table->index('batch_no', 'batch_no');
            $table->index('tour_no', 'tour_no');
            $table->index('status', 'status');
            $table->index('type', 'type');
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
        Schema::dropIfExists('tracking_order_package');
    }
}
