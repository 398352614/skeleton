<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompanyConfigAddColumnAddressTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_config', function (Blueprint $table) {
            $table->smallInteger('address_template_id')->default(null)->nullable()->after('line_rule')->comment('地址模板ID');
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
            $table->dropColumn('address_template');
        });
    }
}
