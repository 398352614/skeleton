<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMerchantAlterColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant', function (Blueprint $table) {
            $table->string('short_name')->default('')->nullable()->comment('公司简称')->after('name')->change();
            $table->string('invoice_title')->default('')->nullable()->comment('发票抬头')->after('avatar')->change();
            $table->string('taxpayer_code')->default('')->nullable()->comment('纳税人识别码')->after('invoice_title')->change();
            $table->string('bank')->default('')->nullable()->comment('开户行')->after('taxpayer_code')->change();
            $table->string('bank_account')->default('')->nullable()->comment('开户账号')->after('bank')->change();
            $table->string('invoice_address')->default('')->nullable()->comment('寄票地址')->after('bank_account')->change();
            $table->string('invoice_email')->default('')->nullable()->comment('收票邮箱')->after('invoice_address')->change();
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
