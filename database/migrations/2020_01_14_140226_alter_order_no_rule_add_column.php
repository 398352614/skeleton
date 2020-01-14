<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderNoRuleAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_no_rule', function (Blueprint $table) {
            $table->string('end_alpha', 1)->default('A')->nullable()->after('start_index')->comment('尾号字母');
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
            $table->dropColumn('end_alpha');
        });
    }
}
