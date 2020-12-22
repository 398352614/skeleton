<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCreatePackageNoRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_no_rule', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('name',50)->default('')->nullable()->comment('规则名称');
            $table->string('prefix', 50)->default('')->nullable()->comment('前缀');
            $table->tinyInteger('length')->default(10)->nullable()->comment('长度限制');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-启用2-禁用');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique(['name', 'company_id'], 'name_company');
            $table->index('company_id', 'company_id');
            $table->index('name', 'name');
            $table->index('status', 'status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_no_rule');
    }
}
