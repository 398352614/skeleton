<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDefaultConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_default_config', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->tinyInteger('type')->default(1)->comment('订单默认类型');
            $table->tinyInteger('settlement_type')->default(1)->comment('订单付款方式');
            $table->tinyInteger('receipt_type')->default(1)->comment('订单回单类型');
            $table->integer('receipt_count')->default(0)->comment('订单回单数量');
            $table->tinyInteger('control_mode')->default(1)->comment('订单控货方式');
            $table->tinyInteger('nature')->default(1)->comment('订单包裹内容');
            $table->tinyInteger('address_template_id')->default(null)->nullable()->comment('地址模板ID');
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
        Schema::dropIfExists('order_default_config');
    }
}
