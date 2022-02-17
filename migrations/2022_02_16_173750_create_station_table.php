<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateStationTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('station', function (Blueprint $table) {
            $table->comment('站点');

            $table->bigIncrements('id');
            $table->string('user_id')->default(null)->nullable()->comment('用户ID');
            $table->string('station_number')->unique()->default('')->nullable()->comment('站点编号');
            $table->string('route_number')->unique()->default('')->nullable()->comment('路线编号');

            $table->string('serial_number')->unique()->default(null)->nullable()->comment('序号');
            $table->integer('distance')->default(null)->nullable()->comment('距离');
            $table->integer('time')->default(null)->nullable()->comment('耗时');
            $table->dateTime('arrive_time')->default(null)->nullable()->comment('抵达时间');
            $table->integer('status')->default(1)->nullable()->comment('状态1-未抵达2-已抵达');

            $table->unique('station_number', 'station_number');
            $table->index('route_number', 'route_number');
            $table->index('serial_number', 'serial_number');
            $table->index('user_id', 'user_id');
            $table->index('status', 'status');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('station');
    }
}
