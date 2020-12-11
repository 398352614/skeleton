<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //订单表
        Schema::table('batch', function (Blueprint $table) {
            $table->renameColumn('receiver_fullname', 'place_fullname');
            $table->renameColumn('receiver_phone', 'place_phone');
            $table->renameColumn('receiver_country', 'place_country');
            $table->renameColumn('receiver_post_code', 'place_post_code');
            $table->renameColumn('receiver_house_number', 'place_house_number');
            $table->renameColumn('receiver_city', 'place_city');
            $table->renameColumn('receiver_street', 'place_street');
            $table->renameColumn('receiver_address', 'place_address');
            $table->renameColumn('receiver_lon', 'place_lon');
            $table->renameColumn('receiver_lat', 'place_lat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
