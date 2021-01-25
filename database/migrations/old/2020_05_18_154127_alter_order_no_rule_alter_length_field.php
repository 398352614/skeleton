<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderNoRuleAlterLengthField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_no_rule', function (Blueprint $table) {
            $table->dropColumn('length');
            $table->smallInteger('int_length')->default(0)->nullable()->after('start_index')->comment('长度');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_no_rule', function (Blueprint $table) {
            $table->dropColumn('int_length');
            $table->smallInteger('length')->default(0)->nullable()->after('start_index')->comment('长度');
        });
    }
}
