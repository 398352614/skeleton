<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderBatchTourTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('batch_no');
            $table->dropColumn('tour_no');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->string('batch_no', 50)->default('')->nullable()->comment('站点编号');
            $table->string('tour_no', 50)->default('')->nullable()->comment('取件线路编号');

            $table->index('batch_no', 'batch_no');
            $table->index('tour_no', 'tour_no');
        });
    }
}
