<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarMaintainDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_maintain_detail', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('maintain_no')->comment('流水号');
            $table->string('maintain_name', 50)->comment('维修项目');
            $table->string('fitting_name', 50)->nullable()->comment('配件名称');
            $table->string('fitting_brand', 50)->nullable()->comment('配件品牌');
            $table->string('fitting_model', 50)->nullable()->comment('配件型号');
            $table->integer('fitting_quantity')->default(0)->comment('数量');
            $table->string('fitting_unit', 5)->default('')->comment('单位');
            $table->decimal('fitting_price')->comment('单价');
            $table->decimal('material_price')->default(0)->comment('材料费');
            $table->decimal('hour_price')->comment('工时费');
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
        Schema::dropIfExists('car_maintain_detail');
    }
}
