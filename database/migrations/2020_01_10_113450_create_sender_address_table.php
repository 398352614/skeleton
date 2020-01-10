<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSenderAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sender_address', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->comment('公司ID');
            $table->string('sender', 50)->default('')->nullable()->comment('发件人姓名');
            $table->string('sender_phone', 20)->default('')->nullable()->comment('发件人电话');
            $table->string('sender_country', 20)->default('')->nullable()->comment('发件人国家');
            $table->string('sender_post_code', 50)->default('')->nullable()->comment('发件人邮编');
            $table->string('sender_house_number', 50)->default('')->nullable()->comment('发件人门牌号');
            $table->string('sender_city', 50)->default('')->nullable()->comment('发件人城市');
            $table->string('sender_street', 50)->default('')->nullable()->comment('发件人街道');
            $table->string('sender_address', 250)->default('')->nullable()->comment('发件人详细地址');
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
        Schema::dropIfExists('sender_address');
    }
}
