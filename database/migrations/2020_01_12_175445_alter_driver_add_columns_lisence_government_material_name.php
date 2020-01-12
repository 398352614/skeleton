<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDriverAddColumnsLisenceGovernmentMaterialName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver', function (Blueprint $table) {
            $table->string('lisence_material_name', 250)->default('')->nullable()->after('lisence_material')->comment('驾照材料名');
            $table->string('government_material_name',250)->default('')->nullable()->after('government_material')->comment('政府材料名');
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
            $table->dropColumn('lisence_material_name');
            $table->dropColumn('government_material_name');
        });
    }
}
