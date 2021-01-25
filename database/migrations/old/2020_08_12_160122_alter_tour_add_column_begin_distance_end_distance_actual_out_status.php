<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTourAddColumnBeginDistanceEndDistanceActualOutStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->integer('begin_distance')->default(0)->nullable()->after('begin_time')->comment('起始公里');
            $table->integer('end_distance')->default(0)->nullable()->after('end_time')->comment('结束公里');
            $table->integer('actual_out_status')->default(2)->nullable()->after('remark')->comment('是否已确认出库');

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
            $table->dropColumn('begin_distance');
            $table->dropColumn('end_distance');
            $table->dropColumn('actual_out_status');
        });
    }
}
