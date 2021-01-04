<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStockExceptionAddColumnOrderNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_exception', function (Blueprint $table) {
            $table->string('order_no',50)->default('')->nullable()->after('tracking_order_no')->comment('订单号');
            $table->index('order_no', 'order_no');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('stock_exception', function (Blueprint $table) {
            $table->dropColumn('order_no');
            $table->dropIndex('order_no');
        });
    }
}
