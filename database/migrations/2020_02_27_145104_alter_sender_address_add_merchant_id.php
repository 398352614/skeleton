<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSenderAddressAddMerchantId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sender_address', function (Blueprint $table) {
            $table->string('merchant_id')->default(null)->nullable()->after('company_id')->comment('商户ID');
            $table->index('merchant_id','merchant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sender_address', function (Blueprint $table) {
            $table->dropIndex('merchant_id');
            $table->dropColumn('merchant_id');
        });
    }
}
