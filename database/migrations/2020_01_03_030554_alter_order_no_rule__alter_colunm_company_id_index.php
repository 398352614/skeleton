<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderNoRuleAlterColunmCompanyIdIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_no_rule', function (Blueprint $table) {
            $table->dropUnique('company_id');
            $table->unique(['company_id','type'], 'company_type');
            $table->index('company_id', 'company_id');
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
            $table->dropIndex('company_id');
            $table->dropUnique('company_type');
            $table->unique(['company_id','type'], 'company_id');
        });
    }
}
