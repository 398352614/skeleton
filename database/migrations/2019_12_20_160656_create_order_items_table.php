<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->integer('company_id')->comment('公司ID');
            $table->string('order_no', 50)->default('')->nullable()->comment('订单号');
            $table->string('name', 50)->default('')->nullable()->comment('货物名称');
            $table->mediumInteger('quantity')->default(0)->nullable()->comment('数量');
            $table->decimal('weight', 8, 2)->default(0.00)->nullable()->comment('重量');
            $table->decimal('volume', 8, 2)->default(0.00)->nullable()->comment('体积');
            $table->decimal('price', 8, 2)->default(0.00)->nullable()->comment('单价');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');
            //索引
            $table->index('company_id', 'company_id');
            $table->index('order_no', 'order_no');
            $table->unique('name', 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
