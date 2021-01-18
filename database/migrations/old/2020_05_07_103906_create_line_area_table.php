<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_area', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('line_id')->default(null)->nullable()->comment('线路ID');
            $table->json('coordinate_list')->default(null)->nullable()->comment('坐标点');
            $table->tinyInteger('schedule')->default(null)->nullable()->comment('取件日期(0-星期日1-星期一2-星期二3-星期三4-星期四5-星期五6-星期六)');
            $table->string('country', 50)->default('')->nullable()->comment('国家');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->index('company_id', 'company_id');
            $table->index('line_id', 'line_id');
            $table->index('schedule', 'schedule');
            $table->index('country', 'country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('line_area');
    }
}
