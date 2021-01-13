<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTimeDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch', function (Blueprint $table) {
            $table->integer('expect_time')->default(0)->after('actual_distance')->comment('预计耗时(秒)')->change();
            $table->integer('actual_time')->default(0)->after('expect_time')->comment('实际耗时耗时(秒)')->change();
        });
        Schema::table('tour', function (Blueprint $table) {
            $table->integer('expect_time')->default(0)->after('actual_distance')->comment('预计耗时(秒)')->change();
            $table->integer('actual_time')->default(0)->after('expect_time')->comment('实际耗时耗时(秒)')->change();
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
