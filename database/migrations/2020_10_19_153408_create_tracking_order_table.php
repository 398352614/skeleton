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
            $table->string('sender_fullname', 50)->default('')->nullable()->comment('发件人姓名');
            $table->string('sender_phone', 50)->default('')->nullable()->comment('发件人手机号码');
            $table->string('sender_country', 50)->default('')->nullable()->comment('发件人国家');
            $table->string('sender_post_code', 50)->default('')->nullable()->comment('发件人邮编');
            $table->string('sender_house_number', 50)->default('')->nullable()->comment('发件人门牌号');
            $table->string('sender_city', 50)->default('')->nullable()->comment('发件人城市');
            $table->string('sender_street', 50)->default('')->nullable()->comment('发件人街道');
            $table->string('sender_address', 50)->default('')->nullable()->comment('发件人地址');
            $table->string('receiver_fullname', 50)->default('')->nullable()->comment('收件人姓名');
            $table->string('receiver_phone', 50)->default('')->nullable()->comment('收件人手机号码');
            $table->string('receiver_country', 50)->default('')->nullable()->comment('收件人国家');
            $table->string('receiver_post_code', 50)->default('')->nullable()->comment('收件人邮编');
            $table->string('receiver_house_number', 50)->default('')->nullable()->comment('收件人门牌号');
            $table->string('receiver_city', 50)->default('')->nullable()->comment('收件人城市');
            $table->string('receiver_street', 50)->default('')->nullable()->comment('收件人街道');
            $table->string('receiver_address', 50)->default('')->nullable()->comment('收件人地址');
            $table->string('lon', 50)->default('')->nullable()->comment('收件人经度');
            $table->string('lat', 50)->default('')->nullable()->comment('收件人纬度');
            $table->integer('driver_id')->default(null)->nullable()->comment('司机ID');
            $table->string('driver_name', 50)->default('')->nullable()->comment('司机姓名');
            $table->string('driver_phone', 50)->default('')->nullable()->comment('司机电话');
            $table->integer('car_id')->default(null)->nullable()->comment('车辆ID');
            $table->string('car_no', 50)->default('')->nullable()->comment('车牌号');
            $table->smallInteger('status')->default(1)->nullable()->comment('运单状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站');
            $table->smallInteger('out_status')->default(1)->nullable()->comment('是否可出库:1-是2-否');
            $table->tinyInteger('exception_label')->default('')->nullable()->comment('标签1-正常2-异常');
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
