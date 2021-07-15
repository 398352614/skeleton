<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRechargeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('merchant_id')->default(null)->nullable()->comment('货主ID');
            $table->string('recharge_no', 50)->default('')->nullable()->comment('充值单号');
            $table->string('transaction_number', 50)->default('')->nullable()->comment('外部充值单号');
            $table->string('out_user_id', 250)->default('')->nullable()->comment('外部用户ID');
            $table->string('out_user_name', 250)->default('')->nullable()->comment('外部用户名');
            $table->string('out_user_phone',250)->default(null)->nullable()->comment('外部用户电话');
            $table->date('recharge_date')->default(null)->nullable()->comment('充值日期');
            $table->dateTime('recharge_time')->default(null)->nullable()->comment('充值时间');
            $table->integer('driver_id')->default(null)->nullable()->comment('司机ID');
            $table->string('driver_name', 250)->default('')->nullable()->comment('司机姓名');
            $table->decimal('recharge_amount', 16, 2)->default(0.00)->nullable()->comment('充值金额');
            $table->string('recharge_first_pic', 250)->default('')->nullable()->comment('充值图片1');
            $table->string('recharge_second_pic', 250)->default('')->nullable()->comment('充值图片2');
            $table->string('recharge_third_pic', 250)->default('')->nullable()->comment('充值图片3');
            $table->string('signature', 250)->default('')->nullable()->comment('充值签名');
            $table->string('remark', 250)->default('')->nullable()->comment('备注');
            $table->tinyInteger('driver_verify_status')->default(1)->nullable()->comment('验证状态1-未验证2-已验证');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-充值中2-充值失败3-充值成功');
            $table->tinyInteger('verify_status')->default(1)->nullable()->comment('审核状态1-未审核2-已审核');
            $table->decimal('verify_recharge_amount', 50,2)->default(0.00)->nullable()->comment('实际金额');
            $table->date('verify_date')->default(null)->nullable()->comment('审核日期');
            $table->dateTime('verify_time')->default(null)->nullable()->comment('审核时间');
            $table->string('verify_remark', 250)->default('')->nullable()->comment('审核备注');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->index('company_id', 'company_id');
            $table->index('merchant_id', 'merchant_id');
            $table->index('recharge_no', 'recharge_no');
            $table->index('status', 'status');
            $table->index('verify_status', 'verify_status');
            $table->index('out_user_id', 'out_user_id');
            $table->index('driver_name', 'driver_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recharge');
    }
}
