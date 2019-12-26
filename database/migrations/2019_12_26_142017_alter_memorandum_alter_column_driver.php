<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMemorandumAlterColumnDriver extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memorandum', function (Blueprint $table) {
            $table->dropIndex('dirver_id');
            $table->dropColumn('dirver_id');
            $table->integer('driver_id')->default(null)->nullable()->after('company_id')->comment('司机ID');
            $table->index('driver_id','driver_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memorandum', function (Blueprint $table) {
            $table->dropIndex('driver_id');
            $table->dropColumn('driver_id');
            $table->integer('dirver_id')->default(null)->nullable()->after('company_id')->comment('司机ID');
            $table->index('dirver_id','dirver_id');
        });
    }
}
