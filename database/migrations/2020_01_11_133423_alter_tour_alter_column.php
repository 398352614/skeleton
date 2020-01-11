<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTourAlterColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->dropColumn('order_amount');
            $table->decimal('sticker_amount')->default(0.00)->nullable()->after('actual_pie_quantity')->comment('贴单费用');
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
            $table->dropColumn('sticker_amount');
            $table->decimal('order_amount')->default(0.00)->nullable()->after('actual_pie_quantity')->comment('贴单费用');
        });
    }
}
