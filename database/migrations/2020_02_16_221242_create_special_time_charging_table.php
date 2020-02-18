<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialTimeChargingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_time_charging', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('transport_price_id')->default(null)->nullable()->comment('运价ID');
            $table->time('start')->default(null)->nullable()->comment('起始时间');
            $table->time('end')->default(null)->nullable()->comment('截止时间');
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
        Schema::dropIfExists('special_time_charging');
    }
}
