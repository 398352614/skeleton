<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBatchAndTourAddColumnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch', function (Blueprint $table) {
            $table->tinyInteger('type')->default(3)->nullable()->after('status')->comment('类型1-取2-派3-取派');
        });
        Schema::table('tour', function (Blueprint $table) {
            $table->tinyInteger('type')->default(3)->nullable()->after('execution_date')->comment('类型1-取2-派3-取派');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('tour', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
