<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTourAddIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->index('execution_date', 'execution_date');
            $table->index('driver_id', 'driver_id');
            $table->index('car_id', 'car_id');
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
            $table->dropIndex('execution_date');
            $table->dropIndex('driver_id');
            $table->dropIndex('car_id');
        });
    }
}
