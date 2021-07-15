<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparePartsRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spare_parts_record', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('sp_no')->comment('备品编号');
            $table->integer('car_id')->default(null)->nullable()->comment('车辆ID');
            $table->string('car_no', 50)->default('')->comment('车牌号');
            $table->decimal('receive_price')->default(0)->comment('单价');
            $table->integer('receive_quantity')->default(0)->comment('领用数量');
            $table->date('receive_date')->default(null)->nullable()->comment('领用时间');
            $table->string('receive_person')->comment('领用人');
            $table->string('receive_remark')->nullable()->comment('备注');
            $table->integer('receive_status')->default(1)->comment('领取状态:1-正常2-已作废');
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
        Schema::dropIfExists('spare_parts_record');
    }
}
