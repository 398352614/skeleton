<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRechargeDeleteColumnVerifyStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recharge', function (Blueprint $table) {
            $table->dropColumn('verify_status');
            $table->dropColumn('verify_recharge_amount');
            $table->dropColumn('verify_date');
            $table->dropColumn('verify_time');
            $table->dropColumn('verify_remark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recharge', function (Blueprint $table) {
            $table->tinyInteger('verify_status')->default(1)->nullable()->comment('审核状态1-未审核2-已审核');
            $table->decimal('verify_recharge_amount', 50, 2)->default(0.00)->nullable()->comment('实际金额');
            $table->date('verify_date')->default(null)->nullable()->comment('审核日期');
            $table->dateTime('verify_time')->default(null)->nullable()->comment('审核时间');
            $table->string('verify_remark', 250)->default('')->nullable()->comment('审核备注');
        });
    }
}
