<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMerchantGroupAddColumnsCountPriceName extends Migration
{
    public function up()
    {
        Schema::table('merchant_group', function (Blueprint $table) {
            $table->integer('count')->default(0)->nullable()->after('transport_price_id')->comment('成员数量');
            $table->string('transport_price_name')->default(null)->nullable()->after('company_id')->comment('运价名称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_group', function (Blueprint $table) {
            $table->dropColumn('count');
            $table->dropColumn('transport_price_name');
        });
    }
}
