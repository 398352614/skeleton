<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderAddColumnStickerNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->string('sticker_no', 50)->default('')->nullable()->after('car_no')->comment('贴单号');
            $table->decimal('sticker_amount')->default(0.00)->nullable()->after('sticker_no')->comment('贴单费用');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('sticker_no');
            $table->dropColumn('sticker_amount');
        });
    }
}
