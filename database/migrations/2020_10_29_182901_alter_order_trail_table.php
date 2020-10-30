<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderTrailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_trail', function (Blueprint $table) {
            $table->rename('tracking_order_trail');
            $table->string('tracking_order_no', 50)->default('')->nullable()->after('order_no')->comment('运单号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracking_order_trail', function (Blueprint $table) {
            $table->rename('order_trail');
        });
    }
}
