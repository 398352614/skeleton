<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceAgreementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_agreement', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('type')->default('')->nullable()->comment('类型');
            $table->text('text')->default('')->nullable()->comment('图片地址');
            $table->string('tittle')->default('')->nullable()->comment('排序ID');
            $table->integer('operator_id')->default(null)->nullable()->comment('操作人ID');
            $table->string('operator_name')->default('')->nullable()->comment('操作人名称');
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
        Schema::dropIfExists('service_agreement');
    }
}
