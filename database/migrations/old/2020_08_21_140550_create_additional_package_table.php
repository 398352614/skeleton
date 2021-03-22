<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_package', function (Blueprint $table) {
            $table->integerIncrements('id');

            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('merchant_id')->default(null)->nullable()->comment('货主ID');
            $table->string('batch_no')->default('')->nullable()->comment('站点编号');
            $table->string('package_no')->default('')->nullable()->comment('包裹编号');
            $table->date('execution_date')->default(null)->nullable()->comment('顺带日期');
            $table->string('receiver_fullname', 50)->default('')->nullable()->comment('收件人姓名');
            $table->string('receiver_phone', 50)->default('')->nullable()->comment('收件人电话');
            $table->string('receiver_country', 50)->default('')->nullable()->comment('收件人国家');
            $table->string('receiver_post_code', 50)->default('')->nullable()->comment('收件人邮编');
            $table->string('receiver_house_number', 50)->default('')->nullable()->comment('收件人门牌号');
            $table->string('receiver_city', 50)->default('')->nullable()->comment('收件人城市');
            $table->string('receiver_street', 50)->default('')->nullable()->comment('收件人街道');
            $table->string('receiver_address', 250)->default('')->nullable()->comment('收件人地址');
            $table->string('receiver_lon', 50)->default('')->nullable()->comment('收件人经度');
            $table->string('receiver_lat', 50)->default('')->nullable()->comment('收件人纬度');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-已推送2-未推送');

            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->index('company_id', 'company_id');
            $table->index('merchant_id', 'merchant_id');
            $table->index('package_no', 'package_no');
            $table->index('execution_date', 'execution_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_package');
    }
}
