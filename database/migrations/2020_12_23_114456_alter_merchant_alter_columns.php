<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMerchantAlterColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant', function (Blueprint $table) {
            $table->string('code', 50)->default('')->nullable()->after('company_id')->comment('用户编码');
            $table->dropColumn('additional_status');
            $table->dropColumn('advance_days');
            $table->dropColumn('appointment_days');
            $table->dropColumn('delay_time');
            $table->dropColumn('pickup_count');
            $table->dropColumn('pie_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
