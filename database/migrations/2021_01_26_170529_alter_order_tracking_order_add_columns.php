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
            $table->integer('distance')->default(0)->nullable()->after('unique_code')->comment('距离');
            $table->decimal('starting_price')->default(0.00)->nullable()->after('distance')->comment('起步价');
            $table->decimal('count_settlement_amount')->default(0.00)->nullable()->after('settlement_amount')->comment('估算运费');
            $table->string('transport_price_id')->default('')->nullable()->after('distance')->comment('运价方案ID');
            $table->string('transport_price_type')->default('')->nullable()->after('transport_price_id')->comment('运价方案类型');
        });
        Schema::table('package', function (Blueprint $table) {
            $table->decimal('settlement_amount')->default(0.00)->nullable()->after('sticker_no')->comment('结算金额');
            $table->decimal('count_settlement_amount')->default(0.00)->nullable()->after('settlement_amount')->comment('估算运费');
            $table->decimal('actual_weight')->default(0.00)->nullable()->after('weight')->comment('实际重量');
        });
        Schema::table('tracking_order_package', function (Blueprint $table) {
            $table->decimal('settlement_amount')->default(0.00)->nullable()->after('sticker_no')->comment('结算金额');
            $table->decimal('count_settlement_amount')->default(0.00)->nullable()->after('settlement_amount')->comment('估算运费');
            $table->decimal('actual_weight')->default(0.00)->nullable()->after('weight')->comment('实际重量');
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
