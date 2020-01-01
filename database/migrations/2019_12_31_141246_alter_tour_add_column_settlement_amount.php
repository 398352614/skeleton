<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTourAddColumnSettlementAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->decimal('settlement_amount', 16, 2)->default(0.00)->nullable()->after('replace_amount')->comment('结算金额-运费');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->dropColumn('settlement_amount');
        });
    }
}
