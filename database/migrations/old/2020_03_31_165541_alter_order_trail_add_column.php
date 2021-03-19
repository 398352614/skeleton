<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderTrailAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_trail', function (Blueprint $table) {
            $table->integer('merchant_id')->default(null)->nullable()->after('company_id')->comment('货主ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_trail', function (Blueprint $table) {
            $table->dropColumn('merchant_id');
        });
    }
}
