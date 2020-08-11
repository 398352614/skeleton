<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLineAddColumnStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('line', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->nullable()->after('appointment_days')->comment('状态1-启用2-禁用');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('line', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
