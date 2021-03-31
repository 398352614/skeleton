<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderAddReceiptTypeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->tinyInteger('receipt_type')->default(1)->nullable()->comment('回单要求')->after('remark');
            $table->integer('receipt_count' )->default(0)->nullable()->comment('回单数量')->after('receipt_type');
            $table->date('create_date')->default(null)->nullable()->comment('开单日期')->after('execution_date');
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
