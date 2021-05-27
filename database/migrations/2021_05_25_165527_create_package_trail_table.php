<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageTrailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_trail', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('merchant_id')->default(null)->nullable()->comment('货主ID');
            $table->string('express_first_no')->default('')->nullable()->comment('包裹单号');
            $table->string('order_no')->default('')->nullable()->comment('订单号');
            $table->string('content')->default('')->nullable()->comment('内容');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->index('company_id', 'company_id');
            $table->index('order_no', 'order_no');
            $table->index('merchant_id', 'merchant_id');
            $table->index('express_first_no', 'express_first_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_trail');
    }
}
