<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('sp_no')->unique()->comment('备品编号');
            $table->string('sp_name')->comment('备品名称');
            $table->string('sp_brand')->comment('备品品牌');
            $table->string('sp_model')->comment('规格型号');
            $table->string('sp_unit')->comment('单位');
            $table->string('operator')->nullable()->comment('操作人');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spare_parts');
    }
}
