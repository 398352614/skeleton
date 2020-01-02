<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTourLogAddColunmsCompanyId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour_log', function (Blueprint $table) {
            $table->integer('company_id')->default(null)->after('id')->nullable()->comment('公司ID');
            $table->unique('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tour_log', function (Blueprint $table) {
            $table->dropIndex('company_id');
            $table->dropColumn('company_id');
            //
        });
    }
}
