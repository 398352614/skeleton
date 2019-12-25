<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_brand', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->comment('公司标识');
            $table->string('cn_name')->comment('品牌名');
            $table->string('en_name')->default('')->nullable()->comment('品牌英文名');
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
        Schema::dropIfExists('car_brand');
    }
}
