<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('name', 100)->default('')->nullable()->comment('费用名称');
            $table->string('code', 50)->default('')->nullable()->comment('费用编码');
            $table->decimal('amount', 8, 2)->default(0.00)->nullable()->comment('费用');
            $table->tinyInteger('level')->default(1)->nullable()->comment('级别1-系统级2-自定义');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-启用2-禁用');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique(['company_id', 'name'], 'name');
            $table->unique(['company_id', 'code'], 'code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee');
    }
}
