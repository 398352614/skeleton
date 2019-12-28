<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderAddCar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->integer('car_id')->default(null)->nullable()->after('driver_name')->comment('车辆ID');
            $table->string('car_no', 50)->default('')->nullable()->after('car_id')->comment('车牌号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('car_id');
            $table->dropColumn('car_no');
        });
    }
}
