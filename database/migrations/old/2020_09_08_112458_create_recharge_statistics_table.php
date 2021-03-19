<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRechargeStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge_statistics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('merchant_id')->default(null)->nullable()->comment('货主ID');
            $table->date('recharge_date')->default(null)->nullable()->comment('充值日期');
            $table->integer('driver_id')->default(null)->nullable()->comment('司机ID');
            $table->string('driver_name', 250)->default('')->nullable()->comment('司机姓名');
            $table->decimal('total_recharge_amount', 16, 2)->default(0.00)->nullable()->comment('充值金额');
            $table->integer('recharge_count')->default(0)->nullable()->comment('充值单数');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-待审核2-已审核');
            $table->date('verify_date')->default(null)->nullable()->comment('审核日期');
            $table->dateTime('verify_time')->default(null)->nullable()->comment('审核时间');
            $table->decimal('verify_recharge_amount', 16,2)->default(0.00)->nullable()->comment('实际金额');
            $table->string('verify_remark', 250)->default('')->nullable()->comment('审核备注');
            $table->string('verify_name',250)->default('')->nullable()->comment('审核人');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique(['company_id', 'merchant_id','recharge_date','driver_id'], 'union');
            $table->index('company_id','company_id');
            $table->index('recharge_date','recharge_date');
            $table->index('merchant_id','merchant_id');
            $table->index('driver_id','driver_id');
            $table->index('status','status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recharge_statistics');
    }
}
