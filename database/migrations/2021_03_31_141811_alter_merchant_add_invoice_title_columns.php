<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMerchantAddInvoiceTitleColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant', function (Blueprint $table) {
            $table->integer('short_name')->default(0)->nullable()->comment('公司简称')->after('name');
            $table->tinyInteger('invoice_title')->default(1)->nullable()->comment('发票抬头')->after('avatar');
            $table->date('taxpayer_code')->default(null)->nullable()->comment('纳税人识别码')->after('invoice_title');
            $table->date('bank')->default(null)->nullable()->comment('开户行')->after('taxpayer_code');
            $table->date('bank_account')->default(null)->nullable()->comment('开户账号')->after('bank');
            $table->date('invoice_address')->default(null)->nullable()->comment('寄票地址')->after('bank_account');
            $table->date('invoice_email')->default(null)->nullable()->comment('收票邮箱')->after('invoice_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
