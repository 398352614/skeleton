<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('user_id')->default(null)->nullable()->comment('用户ID');
            $table->tinyInteger('user_type')->default(3)->nullable()->comment('用户类型1-公司2-后台3-货主4-司机5-客户');
            $table->decimal('balance')->default(0)->nullable()->comment('余额');
            $table->decimal('credit')->default(0)->nullable()->comment('信用额度');
            $table->date('create_date')->default(null)->nullable()->comment('创建日期');
            $table->tinyInteger('pay_type')->default(1)->nullable()->comment('结算方式1-单结2-日结3-周结4-月结');
            $table->tinyInteger('verify_type')->default(1)->nullable()->comment('审核方式1-自动审核2-手动审核');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-无限透支2-有限透支');

            $table->index('company_id', 'company_id');
            $table->index('user_id', 'user_id');
            $table->index('user_type', 'user_type');

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
        Schema::dropIfExists('ledger');
    }
}
