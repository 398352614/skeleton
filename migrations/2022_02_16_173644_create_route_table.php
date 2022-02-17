<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateRouteTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('route', function (Blueprint $table) {
            $table->comment('路线');

            $table->bigIncrements('id');
            $table->string('user_id')->default(null)->nullable()->comment('用户ID');
            $table->string('route_number')->unique()->default(null)->nullable()->comment('路线编号');
            $table->integer('distance')->default(null)->nullable()->comment('距离');
            $table->integer('time')->default(null)->nullable()->comment('耗时');
            $table->dateTime('arrive_time')->default(null)->nullable()->comment('抵达时间');
            $table->integer('sort_times')->default(0)->nullable()->comment('排序次数');

            $table->unique('route_number', 'route_number');
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
        Schema::dropIfExists('route');
    }
}
