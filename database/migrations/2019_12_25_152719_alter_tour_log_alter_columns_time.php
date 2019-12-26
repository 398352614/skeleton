<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTourLogAlterColumnsTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour_log', function (Blueprint $table) {
            $table->dateTime('created_at')->after('status')->default(null)->nullable()->comment('创建时间')->change();
            $table->dateTime('updated_at')->after('created_at')->default(null)->nullable()->comment('修改时间')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
