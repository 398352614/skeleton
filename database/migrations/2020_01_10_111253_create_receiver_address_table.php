<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiverAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receiver_address', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->comment('公司ID');
            $table->string('receiver', 50)->default('')->nullable()->comment('收件人姓名');
            $table->string('receiver_phone', 20)->default('')->nullable()->comment('收件人电话');
            $table->string('receiver_country', 20)->default('')->nullable()->comment('收件人国家');
            $table->string('receiver_post_code', 50)->default('')->nullable()->comment('收件人邮编');
            $table->string('receiver_house_number', 50)->default('')->nullable()->comment('收件人门牌号');
            $table->string('receiver_city', 50)->default('')->nullable()->comment('收件人城市');
            $table->string('receiver_street', 50)->default('')->nullable()->comment('收件人街道');
            $table->string('receiver_address', 250)->default('')->nullable()->comment('收件人详细地址');
            $table->string('lon', 50)->default('')->nullable()->comment('经度');
            $table->string('lat', 50)->default('')->nullable()->comment('纬度');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->index('company_id', 'company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receiver_address');
    }
}
