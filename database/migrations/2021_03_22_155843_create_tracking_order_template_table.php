<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingOrderTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_template', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->comment('公司ID');
            $table->tinyInteger('type')->default(1)->comment('模板类型1-模板一2-模板二');
            $table->tinyInteger('destination_mode')->default(1)->comment('目的地模式1-省市区2-省市3-市区4-邮编');
            $table->string('logo')->default('')->nullable()->comment('标志');
            $table->string('sender')->default('')->nullable()->comment('发货方');
            $table->string('receiver')->default('')->nullable()->comment('收货方');
            $table->string('destination')->default('')->nullable()->comment('目的地');
            $table->string('carrier')->default('')->nullable()->comment('承运人');
            $table->string('carrier_address')->default('')->nullable()->comment('承运人地址');
            $table->string('contents')->default('')->nullable()->comment('物品信息');
            $table->string('package')->default('')->nullable()->comment('包裹');
            $table->string('material')->default('')->nullable()->comment('材料');
            $table->string('count')->default('')->nullable()->comment('数量');
            $table->string('replace_amount')->default('')->nullable()->comment('代收货款');
            $table->string('settlement_amount')->default('')->nullable()->comment('运费金额');
            $table->timestamps();

            $table->index('company_id','company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracking_order_template');
    }
}
