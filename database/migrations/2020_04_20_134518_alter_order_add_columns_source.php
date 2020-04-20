<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderAddColumnsSource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('source');
            $table->smallInteger('source')->default(1)->nullable()->after('type')->comment('来源1-手动添加2-批量导入3-第三方');
            $table->smallInteger('list_mode')->default(1)->nullable()->after('source')->comment('清单模式1-简易模式2-列表模式');
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
            $table->dropColumn('list_mode');
            $table->smallInteger('source')->default(1)->nullable()->after('type')->comment('来源1-手动添加2-批量导入3-第三方');
        });
    }
}
