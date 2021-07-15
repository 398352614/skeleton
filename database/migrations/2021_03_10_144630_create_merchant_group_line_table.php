<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantGroupLineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_group_line', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('line_id')->default(null)->nullable()->comment('线路ID');
            $table->integer('merchant_group_id')->default(null)->nullable()->comment('货主组ID');
            $table->smallInteger('pickup_min_count')->default(0)->nullable()->comment('派件最大订单量');
            $table->smallInteger('pie_min_count')->default(0)->nullable()->comment('派件最大订单量');
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
        Schema::dropIfExists('merchant_group_line');
    }
}
