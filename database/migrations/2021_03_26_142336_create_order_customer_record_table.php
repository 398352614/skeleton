<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCustomerRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_customer_record', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('order_no', 50)->default('')->nullable()->comment('订单号');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->text('content')->nullable()->comment('文字');
            $table->text('file_urls')->nullable()->comment('文件链接');
            $table->text('picture_urls')->nullable()->comment('图片链接');
            $table->string('operator_id')->nullable()->comment('操作人ID');
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
        Schema::dropIfExists('order_customer_record');
    }
}
