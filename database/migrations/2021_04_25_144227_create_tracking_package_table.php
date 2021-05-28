<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_package', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->integer('merchant_id')->default(null)->nullable()->comment('商户ID');
            $table->string('express_first_no')->default('')->nullable()->comment('包裹号');
            $table->string('order_no')->default('')->nullable()->comment('订单号');
            $table->string('bag_no',50)->default('')->nullable()->comment('袋号');
            $table->string('shift_no',50)->default('')->nullable()->comment('车次号');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态:1-待装袋2-待装车3-待发车4-运输中5-待卸车6-待拆袋7-已完成');
            $table->tinyInteger('type')->default(1)->nullable()->comment('类型:1-分拨2-中转');
            $table->tinyInteger('distance_type')->default(1)->nullable()->comment('距离类型:1-长途2-短途');
            $table->decimal('weight', 16,2)->default(0.00)->nullable()->comment('包裹重量');

            $table->integer('warehouse_id')->default(null)->nullable()->comment('所在网点ID');
            $table->string('warehouse_name')->default('')->nullable()->comment('所在网点名称');
            $table->integer('next_warehouse_id')->default(null)->nullable()->comment('目的地网点ID');
            $table->string('next_warehouse_name')->default('')->nullable()->comment('目的地网点名称');

            $table->dateTime('pack_time')->default(null)->nullable()->comment('装袋时间');
            $table->string('pack_operator')->default('')->nullable()->comment('装袋操作人');
            $table->integer('pack_operator_id')->default(null)->nullable()->comment('装袋操作人ID');
            $table->dateTime('unpack_time')->default(null)->nullable()->comment('拆袋时间');
            $table->string('unpack_operator')->default('')->nullable()->comment('拆袋操作人');
            $table->integer('unpack_operator_id')->default(null)->nullable()->comment('拆袋操作人ID');


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
