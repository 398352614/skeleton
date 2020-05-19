<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRouteTrackingAddColumnTourDriverEventId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('route_tracking', function (Blueprint $table) {
        $table->integer('tour_driver_event_id')->nullable()->after('driver_id')->comment('派送事件ID');
        $table->timestamp('time')->nullable()->after('tour_driver_event_id')->comment('时间');
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
            $table->dropColumn('tour_driver_event_id');
            $table->dropColumn('time');
        });
    }
}
