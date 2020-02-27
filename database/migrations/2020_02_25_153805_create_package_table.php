<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('tour_no', 50)->default('')->nullable()->comment('取件线路编号');
            $table->string('batch_no', 50)->default('')->nullable()->comment('站点编号');
            $table->string('order_no', 50)->default('')->nullable()->comment('订单编号');
            $table->string('name', 50)->default('')->nullable()->comment('包裹名称');
            $table->string('express_first_no', 50)->default('')->nullable()->comment('快递单号1');
            $table->string('express_second_no', 50)->default('')->nullable()->comment('快递单号2');
            $table->string('out_order_no', 50)->default('')->nullable()->comment('外部订单号/标识');
            $table->decimal('weight', 8, 2)->default(0.00)->nullable()->comment('重量');
            $table->smallInteger('quantity')->default(1)->nullable()->comment('数量');
            $table->string('remark', 250)->default('')->nullable()->comment('备注');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique(['order_no', 'name'], 'order_no_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package');
    }
}
