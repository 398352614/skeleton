<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('map_config', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->tinyInteger('front_type')->default(1)->comment('前端类型1-谷歌2-百度3-腾讯');
            $table->tinyInteger('back_type')->default(1)->comment('后端类型1-谷歌2-百度3-腾讯');
            $table->tinyInteger('mobile_type')->default(1)->comment('移动端类型1-谷歌2-百度3-腾讯');
            $table->string('google_key')->default('')->nullable()->comment('谷歌key');
            $table->string('google_secret')->default('')->nullable()->comment('谷歌secret');
            $table->string('baidu_key')->default('')->nullable()->comment('百度key');
            $table->string('baidu_secret')->default('')->nullable()->comment('百度secret');
            $table->string('tencent_key')->default('')->nullable()->comment('腾讯key');
            $table->string('tencent_secret')->default('')->nullable()->comment('腾讯secret');
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
        Schema::dropIfExists('map_config');
    }
}
