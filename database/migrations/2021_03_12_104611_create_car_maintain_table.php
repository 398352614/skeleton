<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarMaintainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_maintain', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('maintain_no')->comment('流水号');
            $table->integer('car_id')->default(null)->nullable()->comment('车辆ID');
            $table->string('car_no', 50)->default('')->comment('车牌号');
            $table->integer('distance')->default(0)->comment('车辆行驶里程');
            $table->tinyInteger('maintain_type')->default(0)->comment('维保类型:1-保养2-维修');
            $table->date('maintain_date')->default(null)->nullable()->comment('维保时间');
            $table->string('maintain_factory', 50)->default('')->nullable()->comment('维修厂名称');
            $table->tinyInteger('is_ticket')->default(2)->comment('是否收票:1-是2-否');
            $table->text('maintain_description')->nullable()->comment('问题描述');
            $table->string('maintain_picture')->nullable()->comment('附件图片');
            $table->decimal('maintain_price')->default(0)->comment('费用总计');
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
        Schema::dropIfExists('car_maintain');
    }
}
