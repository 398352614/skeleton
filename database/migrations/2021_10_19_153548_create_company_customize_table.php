<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyCustomizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_customize', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-开启2-禁用');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('admin_url')->default('')->nullable()->comment('管理员端域名');
            $table->string('admin_login_background')->default('')->nullable()->comment('管理员端登录背景');
            $table->string('admin_login_title')->default('')->nullable()->comment('管理员端登录标题');
            $table->string('admin_main_logo')->default('')->nullable()->comment('管理员端主界面logo');
            $table->string('merchant_url')->default('')->nullable()->comment('货主端域名');
            $table->string('merchant_login_background')->default('')->nullable()->comment('货主端登录背景');
            $table->string('merchant_login_title')->default('')->nullable()->comment('货主端登录标题');
            $table->string('merchant_main_logo')->default('')->nullable()->comment('货主端主界面logo');
            $table->string('driver_login_title')->default('')->nullable()->comment('司机端主界面logo');
            $table->string('consumer_url')->default('')->nullable()->comment('客户端域名');
            $table->string('consumer_login_title')->default('')->nullable()->comment('货主端主界面logo');
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
        Schema::dropIfExists('company_customize');
    }
}
