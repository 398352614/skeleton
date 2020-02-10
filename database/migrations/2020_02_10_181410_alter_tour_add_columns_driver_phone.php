<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTourAddColumnsDriverPhone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->string('driver_phone', 50)->default('')->nullable()->after('driver_name')->comment('司机电话');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->dropColumn('driver_phone');
        });
    }
}
