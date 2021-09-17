<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgerLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger_log', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('ledger_id')->default(null)->nullable()->comment('财务账户ID');
            $table->integer('user_id')->default(null)->nullable()->comment('用户ID');
            $table->tinyInteger('user_type')->default(3)->nullable()->comment('用户类型1-公司2-后台3-司机4-货主5-客户');
            $table->string('user_name')->default(null)->nullable()->comment('用户名称');
            $table->string('user_code')->default(null)->nullable()->comment('用户编号');

            $table->decimal('credit')->default(0)->nullable()->comment('信用额度');
            $table->tinyInteger('pay_type')->default(1)->nullable()->comment('结算方式1-单结2-日结3-周结4-月结');
            $table->tinyInteger('verify_type')->default(1)->nullable()->comment('审核方式1-自动审核2-手动审核');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-无限透支2-有限透支');

            $table->string('operator_type')->default(null)->nullable()->comment('操作人类型1-公司2-后台3-司机4-货主5-客户');
            $table->string('operator_id')->default(null)->nullable()->comment('操作人ID');
            $table->string('operator_name')->default('')->nullable()->comment('操作人名称');

            $table->index('company_id', 'company_id');
            $table->index('user_id', 'merchant_id');

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
        Schema::dropIfExists('merchant_credit_log');
    }
}
