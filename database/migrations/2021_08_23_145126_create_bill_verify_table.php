<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillVerifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_verify', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('verify_no')->default(null)->nullable()->comment('审核编号');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-未审核2-已审核3-拒绝');
            $table->tinyInteger('pay_type')->default(null)->nullable()->comment('支付方式1-银行转账2-支票3-现金4-余额');
            $table->decimal('expect_amount')->default(0)->nullable()->comment('应付金额');
            $table->decimal('actual_amount')->default(0)->nullable()->comment('实付金额');
            $table->string('remark')->default(null)->nullable()->comment('备注');
            $table->string('picture_list')->default('')->nullable()->comment('图片');
            $table->dateTime('verify_time')->default(null)->nullable()->comment('审核时间');

            $table->integer('operator_id')->default(null)->nullable()->comment('操作人ID');
            $table->tinyInteger('operator_type')->default(null)->nullable()->comment('操作人类型1-公司2-后台3-司机4-货主5-客户');
            $table->string('operator_name')->default('')->nullable()->comment('操作人名称');

            $table->index('status', 'status');
            $table->index('company_id', 'company_id');
            $table->index('verify_no', 'verify_no');
            $table->index('operator_id', 'operator_id');
            $table->index('operator_type', 'operator_type');

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
        Schema::dropIfExists('bill_settlement');
    }
}
