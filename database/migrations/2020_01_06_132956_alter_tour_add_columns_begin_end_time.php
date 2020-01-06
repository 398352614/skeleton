<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTourAddColumnsBeginEndTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->dateTime('begin_time')->after('status')->default(null)->nullable()->comment('出库时间');
            $table->dateTime('end_time')->after('begin_signature_third_pic')->default(null)->nullable()->comment('入库时间');
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
            $table->dropColumn('begin_time');
            $table->dropColumn('end_time');
        });
    }
}
