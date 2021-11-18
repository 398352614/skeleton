<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_config', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('waiting_time')->default(null)->nullable()->comment('等待时间(秒)');
            $table->tinyInteger('paypal_sandbox_mode')->default(1)->nullable()->comment('paypal沙盒模式1是2否');
            $table->string('paypal_client_id')->default('')->nullable()->comment('paypal应用ID');
            $table->string('paypal_client_secret')->default('')->nullable()->comment('paypal应用秘钥');
            $table->tinyInteger('paypal_status')->default(null)->nullable()->comment('paypal状态1-启用2-禁用');
            $table->timestamps();

            $table->index('company_id', 'company_id');
            $table->index('paypal_sandbox_mode', 'paypal_sandbox_mode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_config');
    }
}
