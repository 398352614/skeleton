<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->tinyInteger('type')->default(1)->nullable()->comment('类型1-个人2-商户');
            $table->string('name', 100)->default('')->nullable()->comment('名称');
            $table->string('email', 50)->default('')->nullable()->comment('邮箱');
            $table->string('password', 100)->default('')->nullable()->comment('密码');
            $table->tinyInteger('settlement_type')->default(1)->nullable()->comment('结算方式1-票结2-日结3-月结');
            $table->integer('merchant_group_id')->default(null)->nullable()->comment('商户组ID');
            $table->string('contacter', 50)->default('')->nullable()->comment('联系人');
            $table->string('phone', 20)->default('')->nullable()->comment('电话');
            $table->string('address', 250)->default('')->nullable()->comment('联系地址');
            $table->string('avatar', 250)->default('')->nullable()->comment('头像');
            $table->tinyInteger('status')->default(1)->nullable()->comment('类型1-启用2-禁用');
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
        Schema::dropIfExists('merchant');
    }
}
