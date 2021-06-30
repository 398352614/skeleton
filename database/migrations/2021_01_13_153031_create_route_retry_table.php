<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteRetryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_retry', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('company_id')->default('')->nullable()->comment('公司ID');
            $table->string('tour_no')->default('')->nullable()->comment('线路任务编号');
            $table->json('data')->default('')->nullable()->comment('参数');
            $table->tinyInteger('retry_times' )->default(0)->nullable()->comment('重试次数');

            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->index('tour_no', 'tour_no');
            $table->index('retry_times', 'retry_times');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_retry');
    }
}
