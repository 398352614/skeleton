<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCountryAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('country', function (Blueprint $table) {
            $table->string('short', 50)->default('')->nullable()->after('company_id')->comment('简称');
            $table->string('tel', 10)->default('')->nullable()->after('cn_name')->comment('区号');
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
            $table->dropColumn('short');
            $table->dropColumn('tel');
        });
    }
}
