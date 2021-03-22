<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPackageMaterialAddColumnsMerchantIdExecutionDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->integer('merchant_id')->default(null)->nullable()->after('company_id')->comment('货主ID');
            $table->date('execution_date')->default(null)->nullable()->after('order_no')->comment('取派日期');
            $table->index('merchant_id', 'merchant_id');
        });
        Schema::table('material', function (Blueprint $table) {
            $table->integer('merchant_id')->default(null)->nullable()->after('company_id')->comment('货主ID');
            $table->date('execution_date')->default(null)->nullable()->after('order_no')->comment('取派日期');
            $table->index('merchant_id', 'merchant_id');
        });
        Schema::table('order', function (Blueprint $table) {
            $table->index('merchant_id', 'merchant_id');
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
            $table->dropIndex('merchant_id');
            $table->dropColumn('merchant_id');
            $table->dropColumn('execution_date');
        });
        Schema::table('material', function (Blueprint $table) {
            $table->dropIndex('merchant_id');
            $table->dropColumn('merchant_id');
            $table->dropColumn('execution_date');
        });
        Schema::table('order', function (Blueprint $table) {
            $table->dropIndex('merchant_id');
        });
    }
}
