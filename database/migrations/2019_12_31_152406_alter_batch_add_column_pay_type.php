<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBatchAddColumnPayType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch', function (Blueprint $table) {
            $table->tinyInteger('pay_type')->default(1)->nullable()->after('signature')->comment('支付方式1-现金支付2-银行支付');
            $table->string('pay_picture', 250)->default('')->nullable()->after('pay_type')->comment('支付图片');
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
            $table->dropColumn('pay_type');
            $table->dropColumn('pay_picture');
        });
    }
}
