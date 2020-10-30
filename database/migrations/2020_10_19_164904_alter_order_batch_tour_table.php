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
            $table->renameColumn('lon', 'receiver_lon');
            $table->renameColumn('lat', 'receiver_lat');
            $table->string('sender_lon', 50)->default('')->nullable()->after('sender_address')->comment('发件人经度');
            $table->string('sender_lat', 50)->default('')->nullable()->after('sender_lon')->comment('发件人纬度');
            $table->date('second_execution_date')->default(null)->nullable()->after('execution_date')->comment('取派订单类型中的派件日期');
        });
        Schema::table('package', function (Blueprint $table) {
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
    }
}
