<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMaterialAlterExpectQuantity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material', function (Blueprint $table) {
            $table->smallInteger('expect_quantity')->default(1)->nullable()->after('name')->comment('预计数量')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('material', function (Blueprint $table) {
            $table->smallInteger('expect_quantity')->default(0)->nullable()->after('out_order_no')->comment('预计数量')->change();
        });
    }
}
