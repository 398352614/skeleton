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
            $table->integer('sticker_no')->default(null)->nullable()->after('execution_date')->comment('贴单号');
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
            $table->dropColumn('sticker_no');
            $table->dropColumn('sticker_amount');
            $table->dropColumn('delivery_amount');
        });
    }
}
