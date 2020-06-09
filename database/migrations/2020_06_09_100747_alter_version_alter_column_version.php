<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVersionAlterColumnVersion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('version', function (Blueprint $table) {
            $table->integer('version')->default(1)->nullable()->after('name')->comment('版本号')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('version', function (Blueprint $table) {
            $table->string('version')->default('')->nullable()->after('name')->comment('版本号')->change();
        });
    }
}
