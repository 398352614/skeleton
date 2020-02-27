<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('tour_no', 50)->default('')->nullable()->comment('取件线路编号');
            $table->string('batch_no', 50)->default('')->nullable()->comment('站点编号');
            $table->string('order_no', 50)->default('')->nullable()->comment('订单编号');
            $table->string('name', 50)->default('')->nullable()->comment('材料名称');
            $table->string('code', 50)->default('')->nullable()->comment('材料代码');
            $table->string('out_order_no', 50)->default('')->nullable()->comment('外部订单号/标识');
            $table->smallInteger('expect_quantity')->default(0)->nullable()->comment('预计数量');
            $table->smallInteger('actual_quantity')->default(0)->nullable()->comment('实际数量');
            $table->string('remark', 250)->default('')->nullable()->comment('备注');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique(['order_no', 'code'], 'order_no_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material');
    }
}
