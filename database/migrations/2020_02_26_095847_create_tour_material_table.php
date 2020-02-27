<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_material', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('tour_no', 50)->default('')->nullable()->comment('取件线路编号');
            $table->string('name', 50)->default('')->nullable()->comment('材料名称');
            $table->string('code', 50)->default('')->nullable()->comment('材料代码');
            $table->smallInteger('expect_quantity')->default(0)->nullable()->comment('预计数量');
            $table->smallInteger('actual_quantity')->default(0)->nullable()->comment('实际数量');
            $table->smallInteger('finish_quantity')->default(0)->nullable()->comment('完成数量');
            $table->smallInteger('surplus_quantity')->default(0)->nullable()->comment('剩余数量');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique(['tour_no', 'code'], 'tour_no_code');
            $table->index('company_id', 'company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tour_material');
    }
}
