<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTour1223 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->integer('expect_distance')->change();
            $table->integer('actual_distance')->change();
            $table->bigInteger('lave_distance')->default(0)->nullable()->comment('剩余里程数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->integer('expect_distance', 16, 2)->change();
            $table->integer('actual_distance', 16, 2)->change();
            $table->dropColumn('lave_distance');
        });
    }
}
