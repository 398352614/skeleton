<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCarAlterColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car', function (Blueprint $table) {
            $table->dropColumn('frame_number');
            $table->dropColumn('engine_number');
            $table->dropColumn('current_miles');
            $table->dropColumn('annual_inspection_date');
            $table->dropColumn('fuel_type');
            $table->dropColumn('received_date');
            $table->dropColumn('month_road_tax');
            $table->dropColumn('transmission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car', function (Blueprint $table) {
            $table->string('frame_number');
            $table->string('engine_number');
            $table->string('current_miles');
            $table->string('annual_inspection_date');
            $table->string('fuel_type');
            $table->string('received_date');
            $table->string('month_road_tax');
            $table->string('transmission');
        });
    }
}
