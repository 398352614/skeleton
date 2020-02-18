<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_price', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('name', 100)->default('')->nullable()->comment('名称');
            $table->decimal('starting_price', 8, 2)->default(0.00)->nullable()->comment('起步价');
            $table->string('remark', 250)->default('')->nullable()->comment('特别说明');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-启用2-禁用');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique(['company_id', 'name'], 'company_id_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transport_price');
    }
}
