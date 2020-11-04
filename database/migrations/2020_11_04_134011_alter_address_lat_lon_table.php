<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddressLatLonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receiver_address', function (Blueprint $table) {
            $table->renameColumn('lat', 'receiver_lat');
            $table->renameColumn('lon', 'receiver_lon');
        });

        Schema::table('sender_address', function (Blueprint $table) {
            $table->renameColumn('lat', 'sender_lat');
            $table->renameColumn('lon', 'sender_lon');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receiver_address', function (Blueprint $table) {
            $table->renameColumn('receiver_lat', 'lat');
            $table->renameColumn('receiver_lon', 'lon');
        });

        Schema::table('sender_address', function (Blueprint $table) {
            $table->renameColumn('sender_lat', 'lat');
            $table->renameColumn('sender_lon', 'lon');
        });
    }
}
