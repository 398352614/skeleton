<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterWarehouseAddColumnsLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse', function (Blueprint $table) {
            $table->string('lon', 50)->default('')->nullable()->after('address')->comment('经度');
            $table->string('lat', 50)->default('')->nullable()->after('address')->comment('纬度');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse', function (Blueprint $table) {
            $table->dropColumn('lon');
            $table->dropColumn('lat');
        });
    }
}
