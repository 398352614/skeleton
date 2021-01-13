<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompanyConfigAddCloumnShowType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_config', function (Blueprint $table) {
            $table->smallInteger('show_type')->default(1)->after('line_rule')->comment('展示方式1-全部展示2-按线路规则展示');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_config', function (Blueprint $table) {
            $table->dropColumn('show_type');
        });
    }
}
