<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTourAddColumnsWarehouseStreetHouseNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->string('warehouse_street', 50)->default('')->nullable()->after('warehouse_city')->comment('仓库街道');
            $table->string('warehouse_house_number',50)->default('')->nullable()->after('warehouse_street')->comment('仓库门牌号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->dropColumn('warehouse_street');
            $table->dropColumn('warehouse_house_number');
        });
    }
}
