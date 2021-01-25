<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderImportLogAddColumnTotalOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_import_log', function (Blueprint $table) {
            $table->integer('total_order')->default(0)->nullable()->after('fail_order')->comment('总订单数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_import_log', function (Blueprint $table) {
            $table->dropColumn('total_order');
        });
    }
}
