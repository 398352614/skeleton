<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAdditionalPackageAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additional_package', function (Blueprint $table) {
            $table->string('tour_no',250)->default(null)->nullable()->after('merchant_id')->comment('线路任务编号');
            $table->integer('line_id')->default(null)->nullable()->after('package_no')->comment('线路ID');
            $table->string('line_name',250)->default('')->nullable()->after('line_id')->comment('线路名称');
            $table->string('sticker_no',250)->default(null)->nullable()->after('execution_date')->comment('贴单号');
            $table->decimal('sticker_amount', 16, 2)->default(0.00)->nullable()->after('sticker_no')->comment('贴单费');
            $table->decimal('delivery_amount', 16, 2)->default(0.00)->nullable()->after('sticker_amount')->comment('提货费');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('additional_package', function (Blueprint $table) {
            $table->dropColumn('line_id');
            $table->dropColumn('line_name');
            $table->dropColumn('tour_no');
            $table->dropColumn('sticker_no');
            $table->dropColumn('sticker_amount');
            $table->dropColumn('delivery_amount');
        });
    }
}
