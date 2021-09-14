<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('bill_no')->default(null)->nullable()->comment('账单编号');
            $table->string('verify_no')->default(null)->nullable()->comment('审核编号');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-未支付2-已支付3-已取消');
            $table->tinyInteger('verify_status')->default(1)->nullable()->comment('审核状态1-未审核2-已审核3-拒绝');
            $table->tinyInteger('object_type')->default(null)->nullable()->comment('关联类型1-订单2-包裹');
            $table->string('object_no')->default(null)->nullable()->comment('关联编号');
            $table->string('remark')->default('')->nullable()->comment('备注');
            $table->string('picture_list')->default('')->nullable()->comment('图片');
            $table->tinyInteger('pay_type')->default(null)->nullable()->comment('支付方式1-银行转账2-支票3-现金4-余额');
            $table->tinyInteger('mode')->default(null)->nullable()->comment('类型1-充值扣款2-运费支付');
            $table->tinyInteger('type')->default(null)->nullable()->comment('详细类型1-运费2-增值费');
            $table->date('create_date')->default(null)->nullable()->comment('创建日期');
            $table->decimal('expect_amount')->default(0)->nullable()->comment('应付金额');
            $table->decimal('actual_amount')->default(0)->nullable()->comment('实付金额');
            $table->dateTime('verify_time')->default(null)->nullable()->comment('审核时间');
            $table->integer('fee_id')->default(null)->nullable()->comment('费用ID');
            $table->string('fee_name')->default(null)->nullable()->comment('费用名称');

            $table->integer('payer_id')->default(null)->nullable()->comment('付款方ID');
            $table->tinyInteger('payer_type')->default(null)->nullable()->comment('付款方类型1-公司2-后台3-货主4-司机5-发件人6-收件人');
            $table->string('payer_name')->default(null)->nullable()->comment('付款方名称');
            $table->integer('payee_id')->default(null)->nullable()->comment('收款方ID');
            $table->tinyInteger('payee_type')->default(null)->nullable()->comment('收款方类型1-公司2-后台3-货主4-司机5-发件人6-收件人');
            $table->string('payee_name')->default(null)->nullable()->comment('收款方名称');
            $table->integer('operator_id')->default(null)->nullable()->comment('操作人ID');
            $table->tinyInteger('operator_type')->default(null)->nullable()->comment('操作人类型1-公司2-后台3-货主4-司机5-发件人6-收件人');
            $table->string('operator_name')->default('')->nullable()->comment('操作人名称');

            $table->tinyInteger('create_timing')->default(null)->nullable()->comment('产生时机1-手动2-下单时3-取货完成时4-派送完成时');
            $table->tinyInteger('pay_timing')->default(1)->nullable()->comment('支付时机1-手动2-下单时3-取货完成时4-派送完成时');

            $table->index('company_id', 'company_id');
            $table->index('bill_no', 'bill_no');
            $table->index('verify_no', 'verify_no');
            $table->index('status', 'status');
            $table->index('verify_status', 'verify_status');
            $table->index('object_no', 'object_no');
            $table->index('object_type', 'object_type');
            $table->index('payer_id', 'payer_id');
            $table->index('payer_type', 'payer_type');
            $table->index('payee_id', 'payee_id');
            $table->index('payee_type', 'payee_type');
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
        Schema::dropIfExists('bill');
    }
}
