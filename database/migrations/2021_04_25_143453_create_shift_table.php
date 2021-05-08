<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('shift_no', 50)->default('')->nullable()->comment('车次号');
            $table->tinyInteger('status')->default(1)->comment('状态:1-未发车2-已发车3-未卸车4-已卸车');
            $table->decimal('weight', 2)->default(0.00)->nullable()->comment('包裹总重量');
            $table->integer('package_count')->default(0)->nullable()->comment('包裹数量');

            $table->string('warehouse_id')->default('')->nullable()->comment('所在网点ID');
            $table->string('warehouse_name')->default('')->nullable()->comment('所在网点名称');
            $table->string('next_warehouse_id')->default('')->nullable()->comment('目的地网点ID');
            $table->string('next_warehouse_name')->default('')->nullable()->comment('目的地网点名称');

            $table->string('driver_id')->default('')->nullable()->comment('司机名称');
            $table->string('driver_name')->default('')->nullable()->comment('司机姓名');
            $table->string('car_no')->default('')->nullable()->comment('车牌号');
            $table->string('car_id')->default('')->nullable()->comment('车辆ID');

            $table->dateTime('begin_time')->default(null)->nullable()->comment('发车时间');
            $table->dateTime('end_time')->default(null)->nullable()->comment('到车时间');
            $table->dateTime('expect_time')->default(null)->nullable()->comment('预计时间');
            $table->dateTime('actual_time')->default(null)->nullable()->comment('实际时间');
            $table->integer('expect_distance')->default(0)->nullable()->comment('预计里程');
            $table->integer('actual_distance')->default(0)->nullable()->comment('实际里程');
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
    }
}
