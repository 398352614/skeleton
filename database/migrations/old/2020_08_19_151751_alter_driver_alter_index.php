<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDriverAlterIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver', function (Blueprint $table) {
            $table->dropIndex('fullname');
            $table->unique(['company_id', 'fullname'], 'company_id_fullname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('driver', function (Blueprint $table) {
            $table->dropIndex('company_id_fullname');
            $table->unique('fullname', 'fullname');
        });
    }
}
