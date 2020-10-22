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
            $table->integer('merchant_id')->default(null)->nullable()->comment('商户ID');
            $table->integer('out_user_id')->default(null)->nullable()->comment('外部客户ID');
            $table->string('out_order_no', 50)->default('')->nullable()->comment('外部订单号');
            $table->string('order_no', 50)->default('')->nullable()->comment('订单号');
            $table->string('tracking_order_no', 50)->default('')->nullable()->comment('运单号');
            $table->string('batch_no', 50)->default('')->nullable()->comment('站点编号');
            $table->string('tour_no', 50)->default('')->nullable()->comment('取件线路编号');
            $table->tinyInteger('type')->default(1)->nullable()->comment('运单类型1-取2-派');
            $table->date('execution_date')->default(null)->nullable()->comment('取派日期');
            $table->string('fullname', 50)->default('')->nullable()->comment('姓名');
            $table->string('receiver_phone', 50)->default('')->nullable()->comment('手机号码');
            $table->string('receiver_country', 50)->default('')->nullable()->comment('国家');
            $table->string('receiver_post_code', 50)->default('')->nullable()->comment('邮编');
            $table->string('receiver_house_number', 50)->default('')->nullable()->comment('门牌号');
            $table->string('receiver_city', 50)->default('')->nullable()->comment('城市');
            $table->string('receiver_street', 50)->default('')->nullable()->comment('街道');
            $table->string('receiver_address', 50)->default('')->nullable()->comment('地址');
            $table->string('lon', 50)->default('')->nullable()->comment('经度');
            $table->string('lat', 50)->default('')->nullable()->comment('纬度');
            $table->integer('driver_id')->default(null)->nullable()->comment('司机ID');
            $table->string('driver_name', 50)->default('')->nullable()->comment('司机姓名');
            $table->string('driver_phone', 50)->default('')->nullable()->comment('司机电话');
            $table->integer('car_id')->default(null)->nullable()->comment('车辆ID');
            $table->string('car_no', 50)->default('')->nullable()->comment('车牌号');
            $table->smallInteger('status')->default(1)->nullable()->comment('运单状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站');
            $table->smallInteger('out_status')->default(1)->nullable()->comment('是否可出库:1-是2-否');
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
