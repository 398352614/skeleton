<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMerchantGroupDeleteCloumnTransportPriceName extends Migration
{
    public function up()
    {
        Schema::table('merchant_group', function (Blueprint $table) {
            $table->dropColumn('transport_price_name');
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
            $table->string('transport_price_name')->default(null)->nullable()->after('company_id')->comment('运价名称');
        });
    }
}
