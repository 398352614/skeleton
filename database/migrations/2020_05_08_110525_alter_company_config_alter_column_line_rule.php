<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterCompanyConfigAlterColumnLineRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_config', function (Blueprint $table) {
            DB::statement("alter table `company_config` MODIFY  COLUMN `line_rule` INT(6) DEFAULT 1 COMMENT '线路规则1-邮编2-区域'");
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
