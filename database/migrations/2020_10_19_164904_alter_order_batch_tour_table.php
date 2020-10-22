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
            $table->dropColumn('driver_id');
            $table->dropColumn('driver_name');
            $table->dropColumn('driver_phone');
            $table->dropColumn('car_id');
            $table->dropColumn('car_no');
            $table->dropColumn('sticker_no');
        });
        Schema::table('package', function (Blueprint $table) {
            $table->string('tracking_order_no', 50)->default('')->nullable()->comment('运单号');
            $table->dropColumn('tour_no');
        });
        Schema::table('material', function (Blueprint $table) {
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
        Schema::table('package', function (Blueprint $table) {
            $table->string('batch_no', 50)->default('')->nullable()->comment('站点编号');
            $table->string('tour_no', 50)->default('')->nullable()->comment('取件线路编号');

            $table->index('batch_no', 'batch_no');
            $table->index('tour_no', 'tour_no');
        });
        Schema::table('material', function (Blueprint $table) {
            $table->string('batch_no', 50)->default('')->nullable()->comment('站点编号');
            $table->string('tour_no', 50)->default('')->nullable()->comment('取件线路编号');

            $table->index('batch_no', 'batch_no');
            $table->index('tour_no', 'tour_no');
        });
    }
}
