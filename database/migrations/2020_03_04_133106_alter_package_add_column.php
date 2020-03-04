<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPackageAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->tinyInteger('type')->default(1)->nullable()->after('order_no')->comment('类型1-取2-派');
            $table->tinyInteger('status')->default(1)->nullable()->after('quantity')->comment('状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站');
            $table->string('sticker_no', 50)->default('')->nullable()->after('status')->comment('贴单号');
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
        Schema::table('package', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('status');
            $table->dropColumn('sticker_no');
            $table->dropColumn('sticker_amount');
        });
    }
}
