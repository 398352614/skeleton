<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderAddColumnsCancel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->tinyInteger('cancel_type')->default(1)->nullable()->after('status')->comment('取消取派-类型1-派送失败(客户不在家)2-另约时间3-其他');
            $table->string('cancel_remark', 250)->default('')->nullable()->after('cancel_type')->comment('取消取派-具体内容');
            $table->string('cancel_picture', 250)->default('')->nullable()->after('cancel_remark')->comment('取消取派-图片');
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
            $table->dropColumn('cancel_type');
            $table->dropColumn('cancel_remark');
            $table->dropColumn('cancel_picture');
        });
    }
}
