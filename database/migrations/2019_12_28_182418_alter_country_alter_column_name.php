<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCountryAlterColumnName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('country', function (Blueprint $table) {
            $table->dropIndex('country_company_id_name_unique');
            $table->dropColumn('name');
            $table->string('cn_name', 50)->default('')->nullable()->after('company_id')->comment('中文名称');
            $table->string('en_name', 50)->default('')->nullable()->after('company_id')->comment('英文名称');
            $table->unique(['company_id', 'cn_name'], 'company_cn_name');
            $table->unique(['company_id', 'en_name'], 'company_en_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('country', function (Blueprint $table) {
            $table->dropIndex('company_cn_name');
            $table->dropIndex('company_en_name');
            $table->dropColumn('cn_name');
            $table->dropColumn('en_name');
            $table->string('name', 50)->default('')->nullable()->after('company_id')->comment('中文名称');
        });
    }
}
