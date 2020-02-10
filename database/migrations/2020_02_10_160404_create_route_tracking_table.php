<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_tracking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->comment('公司ID');
            $table->string('lon', 50)->default('')->nullable()->comment('经度');
            $table->string('lat', 50)->default('')->nullable()->comment('纬度');
            $table->string('tour_no', 50)->default('')->nullable()->comment('在途编号');
            $table->integer('driver_id')->default(null)->nullable()->comment('司机ID');

            $table->index('company_id', 'company_id');
            $table->index('driver_id', 'driver_id');
            $table->index('tour_no', 'tour_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_tracking');
    }
}
