<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeightChargingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weight_charging', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('transport_price_id')->default(null)->nullable()->comment('运价ID');
            $table->integer('start')->default(null)->nullable()->comment('起始重量');
            $table->integer('end')->default(null)->nullable()->comment('截止重量');
            $table->decimal('price', 8, 2)->default(0.00)->nullable()->comment('加价');
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
        Schema::dropIfExists('weight_charging');
    }
}
