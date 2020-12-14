<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddIndexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->index('express_first_no');
            $table->index('status');
        });
        Schema::table('order_trail', function (Blueprint $table) {
            $table->index('merchant_id');
        });
    }
}
