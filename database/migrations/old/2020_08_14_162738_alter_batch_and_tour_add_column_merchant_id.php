<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBatchAndTourAddColumnMerchantId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch', function (Blueprint $table) {
            $table->integer('merchant_id')->default(0)->nullable()->after('company_id')->comment('货主ID');
        });
        Schema::table('tour', function (Blueprint $table) {
            $table->integer('merchant_id')->default(0)->nullable()->after('company_id')->comment('货主ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch', function (Blueprint $table) {
            $table->dropColumn('merchant_id');
        });
        Schema::table('tour', function (Blueprint $table) {
            $table->dropColumn('merchant_id');
        });
    }
}
