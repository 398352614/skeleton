<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRouteTrackingAlterColumnTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('route_tracking', function (Blueprint $table) {
            $table->dropColumn('time');
        });
        Schema::table('route_tracking', function (Blueprint $table) {
            $table->string('time')->nullable()->after('tour_driver_event_id')->comment('时间');
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
            $table->dropColumn('time');
        });
        Schema::table('route_tracking', function (Blueprint $table) {
            $table->timestamp('time')->nullable()->after('tour_driver_event_id')->comment('时间');
        });
    }
}
