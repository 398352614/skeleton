<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tour_no')->comment('在途的唯一标识');
            $table->tinyInteger('action')->comment('对在途进行的操作');
            $table->tinyInteger('status')->default(1)->nullable()->comment('日志的状态, 1 为进行中 2 为已完成 3 为异常');
            // $table->timestamp('timestamp')->comment('日志的');
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
        Schema::dropIfExists('tour_log');
    }
}
