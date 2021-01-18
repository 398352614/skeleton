<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRouteTrackingAddColumnStopTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('route_tracking', function (Blueprint $table) {
            $table->integer('stop_time')->default(0)->after('time')->comment('停留时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('route_tracking', function (Blueprint $table) {
            $table->dropColumn('stop_time');
        });
    }
}
