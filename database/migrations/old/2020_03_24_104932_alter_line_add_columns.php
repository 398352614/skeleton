<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLineAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('line', function (Blueprint $table) {
            $table->dropColumn('order_max_count');
            $table->smallInteger('pickup_max_count')->default(1)->nullable()->after('warehouse_id')->comment('取件最大订单量');
            $table->smallInteger('pie_max_count')->default(1)->nullable()->after('pickup_max_count')->comment('派件最大订单量');
            $table->tinyInteger('is_increment')->default(1)->nullable()->after('pie_max_count')->comment('是否新增线路任务1-是2-否');
            $table->time('order_deadline')->default('23:59:59')->nullable()->after('is_increment')->comment('当天下单截止时间');
            $table->smallInteger('appointment_days')->default(30)->nullable()->after('order_deadline')->comment('可预约天数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('line', function (Blueprint $table) {
            $table->smallInteger('order_max_count')->default(0)->nullable()->after('warehouse_id')->comment('最大订单量');
            $table->dropColumn('pickup_max_count');
            $table->dropColumn('pie_max_count');
            $table->dropColumn('is_increment');
            $table->dropColumn('order_deadline');
            $table->dropColumn('appointment_days');
        });
    }
}
