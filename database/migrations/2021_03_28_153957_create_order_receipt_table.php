<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_receipt', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('order_no', 50)->default('')->nullable()->comment('订单号');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('file_name')->nullable()->comment('文件名');
            $table->string('file_type')->nullable()->comment('文件类型');
            $table->integer('file_size')->nullable()->comment('文件大小');
            $table->string('file_url')->nullable()->comment('文件链接');
            $table->integer('operator_id')->nullable()->comment('操作人ID');
            $table->string('operator_type')->nullable()->comment('操作人类型');
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
        Schema::dropIfExists('order_receipt');
    }
}
