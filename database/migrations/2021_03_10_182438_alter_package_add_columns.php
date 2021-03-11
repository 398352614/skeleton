<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPackageAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->integer('expiration_date')->default(null)->nullable()->after('second_execution_date')->comment('保质期');
            $table->integer('expiration_status')->default(null)->nullable()->after('second_execution_date')->comment('超期状态1-未超期2-已超期3-超期已处理');
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
