<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderAlterColumnsDataType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->smallInteger('cancel_type')->default(null)->nullable()->after('status')->comment('取消取派-类型1-派送失败(客户不在家)2-另约时间3-其他')->change();
            $table->decimal('replace_amount',16,2)->default(0.00)->nullable()->after('settlement_amount')->comment('代收货款')->change();
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
