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
            $table->date('expiration_date')->default(null)->nullable()->after('second_execution_date')->comment('有效日期');
            $table->integer('expiration_status')->default(1)->nullable()->after('expiration_date')->comment('超期状态1-未超期2-已超期3-超期已处理');
        });
        Schema::table('stock', function (Blueprint $table) {
            $table->date('expiration_date')->default(null)->nullable()->after('execution_date')->comment('有效日期');
            $table->integer('expiration_status')->default(1)->nullable()->after('expiration_date')->comment('超期状态1-未超期2-已超期3-超期已处理');
        });
        Schema::table('tracking_order_package', function (Blueprint $table) {
            $table->date('expiration_date')->default(null)->nullable()->after('execution_date')->comment('有效日期');
            $table->integer('expiration_status')->default(1)->nullable()->after('expiration_date')->comment('超期状态1-未超期2-已超期3-超期已处理');
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
