<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('version', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',250)->default('TMS')->nullable()->comment('名称');
            $table->string('version')->default('')->comment('版本号');
            $table->integer('status')->default(1)->comment('状态（1-强制更新，2可选更新）');
            $table->text('url')->comment('下载链接');
            $table->longtext('change_log')->nullable()->comment('更新日志');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('version');
    }
}
