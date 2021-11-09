<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarouselTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carousel', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-开启2-禁用');
            $table->string('name')->default('')->nullable()->comment('图片名称');
            $table->string('picture_url')->default('')->nullable()->comment('图片地址');
            $table->string('sort_id')->default('')->nullable()->comment('排序ID');
            $table->integer('rolling_time')->default(0)->nullable()->comment('滚动时间(秒)');
            $table->tinyInteger('jump_type')->default(1)->nullable()->comment('跳转类型1-外部跳转2-内部跳转');
            $table->tinyInteger('inside_jump_type')->default(null)->nullable()->comment('内部跳转类型');
            $table->string('outside_jump_url')->default('')->nullable()->comment('外部跳转链接');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carousel');
    }
}
