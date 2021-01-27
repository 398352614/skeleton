<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderTrackingOrderAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->string('distance')->default('')->nullable()->after('unique_code')->comment('距离');
            $table->string('starting_price')->default('')->nullable()->after('distance')->comment('起步价');
            $table->string('count_settlement_amount')->default('')->nullable()->after('settlement_amount')->comment('估算运费');
        });
        Schema::table('package', function (Blueprint $table) {
            $table->string('settlement_amount')->default('')->nullable()->after('sticker_no')->comment('结算金额');
            $table->string('count_settlement_amount')->default('')->nullable()->after('settlement_amount')->comment('估算运费');
            $table->string('actual_weight')->default('')->nullable()->after('weight')->comment('实际重量');
        });
        Schema::table('tracking_order_package', function (Blueprint $table) {
            $table->string('settlement_amount')->default('')->nullable()->after('sticker_no')->comment('结算金额');
            $table->string('count_settlement_amount')->default('')->nullable()->after('settlement_amount')->comment('估算运费');
            $table->string('actual_weight')->default('')->nullable()->after('weight')->comment('实际重量');
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
