<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterLineAddColumnRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('line', function (Blueprint $table) {
            $table->smallInteger('rule')->default(1)->nullable()->after('company_id')->comment('1-邮编2-区域');
            $table->index('rule', 'rule');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('line', function (Blueprint $table) {
            $table->dropColumn('rule');
        });
    }
}
