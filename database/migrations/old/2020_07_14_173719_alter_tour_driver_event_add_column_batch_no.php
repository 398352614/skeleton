<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTourDriverEventAddColumnBatchNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour_driver_event', function (Blueprint $table) {
            $table->string('batch_no')->default('')->nullable()->after('icon_path')->comment('站点编号');
            $table->index('batch_no','batch_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tour_driver_event', function (Blueprint $table) {
            $table->dropIndex('batch_no');
            $table->dropColumn('batch_no');
        });
    }
}
