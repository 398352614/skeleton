<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_order', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('merchant_id')->default(null)->nullable()->comment('货主ID');
            $table->string('out_user_id')->default('')->nullable()->comment('客户单号');
            $table->string('out_order_no', 50)->default('')->nullable()->comment('货号');
            $table->string('order_no', 50)->default('')->nullable()->comment('订单号');
            $table->string('tracking_order_no', 50)->default('')->nullable()->comment('运单号');
            $table->string('batch_no', 50)->default('')->nullable()->comment('站点编号');
            $table->string('tour_no', 50)->default('')->nullable()->comment('线路任务编号');
            $table->integer('line_id')->default(null)->nullable()->comment('线路ID');
            $table->string('line_name', 50)->default('')->nullable()->comment('线路名称');
            $table->tinyInteger('type')->default(1)->nullable()->comment('运单类型1-取2-派');
            $table->date('execution_date')->default(null)->nullable()->comment('取派日期');
            $table->string('warehouse_fullname', 50)->default('')->nullable()->comment('网点-姓名');
            $table->string('warehouse_phone', 50)->default('')->nullable()->comment('网点-手机号码');
            $table->string('warehouse_country', 50)->default('')->nullable()->comment('网点-国家');
            $table->string('warehouse_post_code', 50)->default('')->nullable()->comment('网点-邮编');
            $table->string('warehouse_house_number', 50)->default('')->nullable()->comment('网点-门牌号');
            $table->string('warehouse_city', 50)->default('')->nullable()->comment('网点-城市');
            $table->string('warehouse_street', 50)->default('')->nullable()->comment('网点-街道');
            $table->string('warehouse_address', 250)->default('')->nullable()->comment('网点-地址');
            $table->string('warehouse_lon', 50)->default('')->nullable()->comment('网点-经度');
            $table->string('warehouse_lat', 50)->default('')->nullable()->comment('网点-纬度');
            $table->string('place_fullname', 50)->default('')->nullable()->comment('地址-姓名');
            $table->string('place_phone', 50)->default('')->nullable()->comment('地址-手机号码');
            $table->string('place_country', 50)->default('')->nullable()->comment('地址-国家');
            $table->string('place_post_code', 50)->default('')->nullable()->comment('地址-邮编');
            $table->string('place_house_number', 50)->default('')->nullable()->comment('地址-门牌号');
            $table->string('place_city', 50)->default('')->nullable()->comment('地址-城市');
            $table->string('place_street', 50)->default('')->nullable()->comment('地址-街道');
            $table->string('place_address', 250)->default('')->nullable()->comment('地址-地址');
            $table->string('place_lon', 50)->default('')->nullable()->comment('地址-经度');
            $table->string('place_lat', 50)->default('')->nullable()->comment('地址-纬度');
            $table->integer('driver_id')->default(null)->nullable()->comment('司机ID');
            $table->string('driver_name', 50)->default('')->nullable()->comment('司机姓名');
            $table->string('driver_phone', 50)->default('')->nullable()->comment('司机电话');
            $table->integer('car_id')->default(null)->nullable()->comment('车辆ID');
            $table->string('car_no', 50)->default('')->nullable()->comment('车牌号');
            $table->smallInteger('status')->default(1)->nullable()->comment('运单状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站');
            $table->smallInteger('out_status')->default(1)->nullable()->comment('是否可出库:1-是2-否');
            $table->tinyInteger('exception_label')->default(1)->nullable()->comment('标签1-正常2-异常');
            $table->smallInteger('cancel_type')->default(null)->nullable()->comment('取消取派-类型1-派送失败(客户不在家)2-另约时间3-其他');
            $table->string('cancel_remark', 50)->default('')->nullable()->comment('取消取派-具体内容');
            $table->string('cancel_picture', 50)->default('')->nullable()->comment('取消取派-图片');
            $table->string('mask_code')->default('')->nullable()->comment('掩码');
            $table->string('special_remark')->default('')->nullable()->comment('特殊事项');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique('tracking_order_no', 'tracking_order_no');
            $table->index('company_id', 'company_id');
            $table->index('merchant_id', 'merchant_id');
            $table->index('execution_date', 'execution_date');
            $table->index('order_no', 'order_no');
            $table->index('batch_no', 'batch_no');
            $table->index('tour_no', 'tour_no');
            $table->index('status', 'status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracking_order');
    }
}
