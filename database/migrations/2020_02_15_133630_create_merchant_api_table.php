<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantApiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_api', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('merchant_id')->default(null)->nullable()->comment('商户ID');
            $table->string('key', 50)->default('')->nullable()->comment('key');
            $table->string('secret', 50)->default('')->nullable()->comment('secret');
            $table->string('url', 250)->default('')->nullable()->comment('推送url');
            $table->string('white_ip_list', 250)->default('')->nullable()->comment('白名单IP列表');
            $table->tinyInteger('status')->default(1)->nullable()->comment('推送1-是2-否');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique(['company_id', 'merchant_id'], 'compnay_merchant');
            $table->unique('key', 'key');
            $table->unique('secret', 'secret');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_api');
    }
}
