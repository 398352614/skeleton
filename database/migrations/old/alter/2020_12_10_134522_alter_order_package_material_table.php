<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderPackageMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //订单表
        Schema::table('order', function (Blueprint $table) {
            //删除字段
            $table->dropColumn('batch_no');
            $table->dropColumn('tour_no');
            $table->dropColumn('driver_id');
            $table->dropColumn('driver_name');
            $table->dropColumn('driver_phone');
            $table->dropColumn('car_id');
            $table->dropColumn('car_no');
            $table->dropColumn('sticker_no');
            $table->dropColumn('express_first_no');
            $table->dropColumn('express_second_no');
            //修改字段
            $table->renameColumn('sender_fullname', 'second_place_fullname');
            $table->renameColumn('sender_phone', 'second_place_phone');
            $table->renameColumn('sender_country', 'second_place_country');
            $table->renameColumn('sender_post_code', 'second_place_post_code');
            $table->renameColumn('sender_house_number', 'second_place_house_number');
            $table->renameColumn('sender_city', 'second_place_city');
            $table->renameColumn('sender_street', 'second_place_street');
            $table->renameColumn('sender_address', 'second_place_address');
            $table->renameColumn('receiver_fullname', 'place_fullname');
            $table->renameColumn('receiver_phone', 'place_phone');
            $table->renameColumn('receiver_country', 'place_country');
            $table->renameColumn('receiver_post_code', 'place_post_code');
            $table->renameColumn('receiver_house_number', 'place_house_number');
            $table->renameColumn('receiver_city', 'place_city');
            $table->renameColumn('receiver_street', 'place_street');
            $table->renameColumn('receiver_address', 'place_address');
            $table->renameColumn('lon', 'place_lon');
            $table->renameColumn('lat', 'place_lat');
            //新增字段
            $table->string('tracking_order_no', 50)->default('')->nullable()->after('order_no')->comment('运单号');
            $table->date('second_execution_date')->default(null)->nullable()->after('execution_date')->comment('取派订单类型中的派件日期');
            $table->string('out_group_order_no', 50)->default('')->nullable()->after('out_order_no')->comment('外部订单组号');
            $table->string('second_place_lon', 50)->default('')->nullable()->after('sender_address')->comment('地址二-经度');
            $table->string('second_place_lat', 50)->default('')->nullable()->after('second_place_lon')->comment('地址二-纬度');
        });
        //包裹表
        Schema::table('package', function (Blueprint $table) {
            //删除字段
            $table->dropColumn('batch_no');
            $table->dropColumn('tour_no');
            //新增字段
            $table->date('second_execution_date')->default(null)->nullable()->after('execution_date')->comment('取派订单类型中的派件日期');
            $table->string('tracking_order_no', 50)->default('')->nullable()->after('order_no')->comment('运单号');
        });
        //材料表
        Schema::table('material', function (Blueprint $table) {
            //删除字段
            $table->dropColumn('batch_no');
            $table->dropColumn('tour_no');
            //新增字段
            $table->string('tracking_order_no', 50)->default('')->nullable()->after('order_no')->comment('运单号');
        });
        //顺带包裹
        Schema::table('additional_package', function (Blueprint $table) {
            $table->renameColumn('receiver_fullname', 'place_fullname');
            $table->renameColumn('receiver_phone', 'place_phone');
            $table->renameColumn('receiver_country', 'place_country');
            $table->renameColumn('receiver_post_code', 'place_post_code');
            $table->renameColumn('receiver_house_number', 'place_house_number');
            $table->renameColumn('receiver_city', 'place_city');
            $table->renameColumn('receiver_street', 'place_street');
            $table->renameColumn('receiver_address', 'place_address');
            $table->renameColumn('receiver_lon', 'place_lon');
            $table->renameColumn('receiver_lat', 'place_lat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
