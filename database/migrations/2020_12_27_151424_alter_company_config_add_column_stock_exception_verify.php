<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompanyConfigAddColumnStockExceptionVerify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_config', function (Blueprint $table) {
            $table->tinyInteger('stock_exception_verify')->default(1)->nullable()->after('address_template_id')->comment('是否开启入库异常审核1-开启2-关闭');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_config', function (Blueprint $table) {
            $table->dropColumn('stock_exception_verify');
        });
    }
}
