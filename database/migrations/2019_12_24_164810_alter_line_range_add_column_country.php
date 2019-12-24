<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLineRangeAddColumnCountry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('line_range', function (Blueprint $table) {
            $table->string('country', 50)->default('')->nullable()->after('schedule')->comment('国家');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('line_range', function (Blueprint $table) {
            $table->dropColumn('country');
        });
    }
}
