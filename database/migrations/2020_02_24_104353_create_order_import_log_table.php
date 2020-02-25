<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderImportLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_import_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('name',50)->default('')->comment('文件名');
            $table->text('url')->nullable()->comment('下载链接');
            $table->string('status',250)->nullable()->comment('状态');
            $table->longText('log')->nullable()->comment('日志');
            $table->integer('success_order')->default(0)->nullable()->comment('导入订单成功数量');
            $table->integer('fail_order')->default(0)->nullable()->comment('导入订单失败数量');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_import_log');
    }
}
