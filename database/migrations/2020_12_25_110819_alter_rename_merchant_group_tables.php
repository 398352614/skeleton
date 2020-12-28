<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRenameMerchantGroupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_fee_config', function (Blueprint $table) {
            $table->renameColumn('merchant_id', 'merchant_group_id');
            $table->rename('merchant_group_fee_config');
        });
        Schema::table('merchant_line_range', function (Blueprint $table) {
            $table->renameColumn('merchant_id', 'merchant_group_id');
            $table->rename('merchant_group_line_range');
        });
        Schema::table('merchant_group', function (Blueprint $table) {
            $table->smallInteger('additional_status')->default(2)->nullable()->after('is_default')->comment('顺带包裹状态1-启用2-禁用');
            $table->smallInteger('advance_days')->default(0)->nullable()->after('additional_status')->comment('须提前下单天数');
            $table->smallInteger('appointment_days')->default(null)->nullable()->after('advance_days')->comment('可预约天数');
            $table->smallInteger('delay_time')->default(0)->nullable()->after('appointment_days')->comment('截止时间延后时间(分钟)');
            $table->smallInteger('pickup_count')->default(1)->nullable()->after('delay_time')->comment('取件次数0-手动');
            $table->smallInteger('pie_count')->default(1)->nullable()->after('pickup_count')->comment('派件次数0-手动');
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
