<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarAccidentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_accident', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('car_id')->default(null)->nullable()->comment('车辆ID');
            $table->string('car_no', 50)->default('')->comment('车牌号');
            $table->integer('driver_id')->default(null)->nullable()->comment('司机ID');
            $table->string('driver_fullname', 50)->default('')->nullable()->comment('司机姓名');
            $table->string('driver_phone')->default('')->nullable()->comment('司机手机号');
            $table->tinyInteger('deal_type')->default(1)->comment('处理方式：1-保险2-公司赔付');
            $table->string('accident_location', 20)->default('')->nullable()->comment('事故地点');
            $table->date('accident_date')->default(null)->nullable()->comment('事故时间');
            $table->tinyInteger('accident_duty')->default(1)->comment('主被动,责任方：1-主动2-被动');
            $table->text('accident_description')->default('')->nullable()->comment('事故描述');
            $table->string('accident_picture')->nullable()->comment('事故照片');
            $table->string('accident_no')->nullable()->comment('事故处理单号')->unique();
            $table->tinyInteger('insurance_indemnity')->default(1)->comment('保险是否赔付：1-是2-否');
            $table->decimal('insurance_payment')->default(0)->comment('保险垫付款');
            $table->text('insurance_description')->default('')->nullable()->comment('赔付描述');
            $table->string('operator')->nullable()->comment('操作人');
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
        Schema::dropIfExists('car_accident');
    }
}
