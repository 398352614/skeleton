<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_config', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('line_rule', 50)->default('')->nullable()->comment('配置名称');
            $table->string('weight_unit', 50)->default('')->nullable()->comment('配置值');
            $table->string('currency_unit', 50)->default('')->nullable()->comment('配置值');
            $table->string('volume_unit', 50)->default('')->nullable()->comment('配置值');
            $table->string('map', 50)->default('')->nullable()->comment('配置值');
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
        Schema::dropIfExists('company_config');
    }
}
