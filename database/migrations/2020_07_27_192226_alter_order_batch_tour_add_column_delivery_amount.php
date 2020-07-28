<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderBatchTourAddColumnDeliveryAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->decimal('delivery_amount')->default(0)->nullable()->after('sticker_amount')->comment('提货费用');
        });
        Schema::table('order', function (Blueprint $table) {
            $table->decimal('delivery_amount')->default(0)->nullable()->after('sticker_amount')->comment('提货费用');
        });
        Schema::table('batch', function (Blueprint $table) {
            $table->decimal('delivery_amount')->default(0)->nullable()->after('sticker_amount')->comment('提货费用');
        });
        Schema::table('tour', function (Blueprint $table) {
            $table->decimal('delivery_amount')->default(0)->nullable()->after('sticker_amount')->comment('提货费用');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->dropColumn('delivery_amount');
        });
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('delivery_amount');
        });
        Schema::table('batch', function (Blueprint $table) {
            $table->dropColumn('delivery_amount');
        });
        Schema::table('tour', function (Blueprint $table) {
            $table->dropColumn('delivery_amount');
        });
    }
}
