<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderNoRuleAlterFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_no_rule', function (Blueprint $table) {
            $table->dropColumn('end_alpha');
            $table->integer('start_index')->default(1)->nullable()->after('prefix')->comment('开始索引')->change();
            $table->smallInteger('length')->default(0)->nullable()->after('start_index')->comment('长度')->change();
            $table->string('start_string_index', 20)->default('')->nullable()->after('length')->comment('开始字符');
            $table->smallInteger('string_length')->default(0)->nullable()->after('start_string_index')->comment('字符数量');
            $table->string('max_no', 30)->default('')->nullable()->after('string_length')->comment('最大单号');
            $table->tinyInteger('status')->default(1)->nullable()->after('max_no')->comment('状态1-启用2-禁用');

            //$table->unique('prefix', 'prefix');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_no_rule', function (Blueprint $table) {
            $table->dropColumn('start_string_index');
            $table->dropColumn('string_length');
            $table->dropColumn('max_no');
            $table->dropColumn('status');
            $table->string('end_alpha', 1)->default('')->nullable()->comment('结束字符');
        });
    }
}
