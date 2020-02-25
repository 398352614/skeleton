<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_driver_event', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->comment('公司ID');
            $table->string('lon', 50)->default('')->nullable()->comment('经度');
            $table->string('lat', 50)->default('')->nullable()->comment('纬度');
            $table->integer('type')->default(0)->nullable()->comment('事件类型 -- 预留');
            $table->string('content')->default('')->nullable()->comment('事件详情');
            $table->integer('icon_id')->default(0)->nullable()->comment('图标 id 预留');
            $table->string('icon_path')->default('')->nullable()->comment('图标url地址');
            $table->string('tour_no', 50)->default('')->nullable()->comment('在途编号');
            $table->integer('route_tracking_id')->default(0)->nullable()->comment('对应的路线追踪中的点,预留');

            $table->index('company_id', 'company_id');
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
        Schema::dropIfExists('tour_driver_event');
    }
}
