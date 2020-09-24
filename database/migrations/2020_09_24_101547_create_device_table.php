<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('driver_id')->default(null)->nullable()->comment('司机ID');
            $table->string('number', 50)->default('')->nullable()->comment('设备型号');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-在线-离线');
            $table->string('mode', 20)->default('GPS')->nullable()->comment('模式');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique('number', 'number');
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
        Schema::dropIfExists('device');
    }
}
