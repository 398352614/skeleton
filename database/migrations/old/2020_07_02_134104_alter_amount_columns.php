<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAmountColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch', function (Blueprint $table) {
            $table->decimal('actual_replace_amount',16,2)->default(0.00)->after('replace_amount')->comment('实际代收货款');
            $table->decimal('actual_settlement_amount',16,2)->default(0.00)->after('settlement_amount')->comment('实际结算金额');
        });
        Schema::table('tour', function (Blueprint $table) {
            $table->decimal('actual_replace_amount',16,2)->default(0.00)->after('replace_amount')->comment('实际代收货款');
            $table->decimal('actual_settlement_amount',16,2)->default(0.00)->after('settlement_amount')->comment('实际结算金额');
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
            $table->dropColumn('actual_replace_amount');
            $table->dropColumn('actual_settlement_amount');
        });
        Schema::table('tour', function (Blueprint $table) {
            $table->dropColumn('actual_replace_amount');
            $table->dropColumn('actual_settlement_amount');
        });
    }
}
