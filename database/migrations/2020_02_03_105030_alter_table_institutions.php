<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInstitutions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->renameColumn('parent_id', 'parent');
            $table->renameIndex('institutions_parent_id_index', 'institutions_parent_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->renameColumn( 'parent','parent_id');
            $table->renameIndex('institutions_parent_index', 'institutions_parent_id_index');
        });
    }
}
