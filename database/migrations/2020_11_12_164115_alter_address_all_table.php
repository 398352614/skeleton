<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddressAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receiver_address', function (Blueprint $table) {
            $table->renameColumn('receiver_fullname', 'place_fullname');
            $table->renameColumn('receiver_phone', 'place_phone');
            $table->renameColumn('receiver_country', 'place_country');
            $table->renameColumn('receiver_house_number', 'place_house_number');
            $table->renameColumn('receiver_post_code', 'place_post_code');
            $table->renameColumn('receiver_city', 'place_city');
            $table->renameColumn('receiver_street', 'place_street');
            $table->renameColumn('receiver_address', 'place_address');
            $table->renameColumn('receiver_lat', 'place_lat');
            $table->renameColumn('receiver_lon', 'place_lon');
        });

        Schema::table('sender_address', function (Blueprint $table) {
            $table->drop();
        });

        Schema::table('order', function (Blueprint $table) {
            $table->renameColumn('receiver_fullname', 'place_fullname');
            $table->renameColumn('receiver_phone', 'place_phone');
            $table->renameColumn('receiver_country', 'place_country');
            $table->renameColumn('receiver_house_number', 'place_house_number');
            $table->renameColumn('receiver_post_code', 'place_post_code');
            $table->renameColumn('receiver_city', 'place_city');
            $table->renameColumn('receiver_street', 'place_street');
            $table->renameColumn('receiver_address', 'place_address');
            $table->renameColumn('receiver_lat', 'place_lat');
            $table->renameColumn('receiver_lon', 'place_lon');

            $table->renameColumn('sender_fullname', 'second_place_fullname');
            $table->renameColumn('sender_phone', 'second_place_phone');
            $table->renameColumn('sender_country', 'second_place_country');
            $table->renameColumn('sender_house_number', 'second_place_house_number');
            $table->renameColumn('sender_post_code', 'second_place_post_code');
            $table->renameColumn('sender_city', 'second_place_city');
            $table->renameColumn('sender_street', 'second_place_street');
            $table->renameColumn('sender_address', 'second_place_address');
            $table->renameColumn('sender_lat', 'second_place_lat');
            $table->renameColumn('sender_lon', 'second_place_lon');
        });

        Schema::table('tracking_order', function (Blueprint $table) {
            $table->renameColumn('receiver_fullname', 'place_fullname');
            $table->renameColumn('receiver_phone', 'place_phone');
            $table->renameColumn('receiver_country', 'place_country');
            $table->renameColumn('receiver_house_number', 'place_house_number');
            $table->renameColumn('receiver_post_code', 'place_post_code');
            $table->renameColumn('receiver_city', 'place_city');
            $table->renameColumn('receiver_street', 'place_street');
            $table->renameColumn('receiver_address', 'place_address');
            $table->renameColumn('receiver_lat', 'place_lat');
            $table->renameColumn('receiver_lon', 'place_lon');

            $table->renameColumn('sender_fullname', 'warehouse_fullname');
            $table->renameColumn('sender_phone', 'warehouse_phone');
            $table->renameColumn('sender_country', 'warehouse_country');
            $table->renameColumn('sender_house_number', 'warehouse_house_number');
            $table->renameColumn('sender_post_code', 'warehouse_post_code');
            $table->renameColumn('sender_city', 'warehouse_city');
            $table->renameColumn('sender_street', 'warehouse_street');
            $table->renameColumn('sender_address', 'warehouse_address');
            $table->renameColumn('sender_lat', 'warehouse_lat');
            $table->renameColumn('sender_lon', 'warehouse_lon');
        });

        Schema::table('batch', function (Blueprint $table) {
            $table->renameColumn('receiver_fullname', 'place_fullname');
            $table->renameColumn('receiver_phone', 'place_phone');
            $table->renameColumn('receiver_country', 'place_country');
            $table->renameColumn('receiver_house_number', 'place_house_number');
            $table->renameColumn('receiver_post_code', 'place_post_code');
            $table->renameColumn('receiver_city', 'place_city');
            $table->renameColumn('receiver_street', 'place_street');
            $table->renameColumn('receiver_address', 'place_address');
            $table->renameColumn('receiver_lat', 'place_lat');
            $table->renameColumn('receiver_lon', 'place_lon');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
