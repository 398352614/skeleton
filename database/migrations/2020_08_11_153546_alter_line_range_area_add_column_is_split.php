<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLineRangeAreaAddColumnIsSplit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('line_range', function (Blueprint $table) {
            $table->tinyInteger('is_split')->default(2)->nullable()->after('country')->comment('是否拆分1-是2-否');
        });
        Schema::table('line_area', function (Blueprint $table) {
            $table->tinyInteger('is_split')->default(2)->nullable()->after('country')->comment('是否拆分1-是2-否');
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
            $table->dropColumn('is_split');
        });
        Schema::table('line_area', function (Blueprint $table) {
            $table->dropColumn('is_split');
        });
    }
}
