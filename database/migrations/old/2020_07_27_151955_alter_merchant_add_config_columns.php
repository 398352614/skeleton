<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMerchantAddConfigColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant', function (Blueprint $table) {
            $table->smallInteger('advance_days')->default(null)->nullable()->after('status')->comment('须提前下单天数');
            $table->smallInteger('appointment_days')->default(null)->nullable()->after('advance_days')->comment('可预约天数');
            $table->smallInteger('delay_time')->default(null)->nullable()->after('appointment_days')->comment('截止时间延后时间(分钟)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant', function (Blueprint $table) {
            $table->dropColumn('advance_days');
            $table->dropColumn('appointment_days');
            $table->dropColumn('delay_time');
        });
    }
}
