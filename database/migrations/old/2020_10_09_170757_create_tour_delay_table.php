<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourDelayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_delay', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');

            $table->string('tour_no', 250)->default('')->nullable()->comment('线路任务编号');
            $table->date('execution_date')->default(null)->nullable()->comment('取派日期');
            $table->integer('line_id')->default(null)->nullable()->comment('线路ID');
            $table->string('line_name',50)->default('')->nullable()->comment('线路名称');
            $table->integer('driver_id')->default(null)->nullable()->comment('司机ID');
            $table->string('driver_name',50)->default('')->nullable()->comment('司机名称');

            $table->integer('delay_time')->default(0)->nullable()->comment('延迟时间');
            $table->tinyInteger('delay_type')->default(4)->nullable()->comment('延迟类型1-用餐休息2-交通堵塞3-更换行车路线4-其他');
            $table->string('delay_remark')->default('')->nullable()->comment('延迟备注');

            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->index('company_id', 'company_id');
            $table->index('driver_id', 'driver_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tour_delay');
    }
}
