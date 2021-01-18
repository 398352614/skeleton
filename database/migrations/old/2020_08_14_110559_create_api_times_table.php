<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_times', function (Blueprint $table) {
            $table->integerIncrements('id');

            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->date('date')->default(null)->nullable()->comment('日期');

            $table->integer('directions_times')->default(0)->nullable()->comment('智能优化数');
            $table->integer('actual_directions_times')->default(0)->nullable()->comment('智能优化成功数');
            $table->integer('api_directions_times')->default(0)->nullable()->comment('智能优化请求第三方数');

            $table->integer('distance_times')->default(0)->nullable()->comment('计算距离数');
            $table->integer('actual_distance_times')->default(0)->nullable()->comment('计算距离成功数');
            $table->integer('api_distance_times')->default(0)->nullable()->comment('计算距离请求第三方数');

            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->index('company_id', 'company_id');
            $table->index('date', 'date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_times');
    }
}
