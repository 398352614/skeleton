<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportPriceOperationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_price_operation', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('company_id')->default('')->nullable()->comment('公司ID');
            $table->integer('transport_price_id')->default(null)->nullable()->comment('运价方案ID');
            $table->string('operation')->default('')->nullable()->comment('操作类型');
            $table->string('operator')->default('')->nullable()->comment('操作人');
            $table->json('content')->default('')->nullable()->comment('内容');
            $table->json('second_content')->default('')->nullable()->comment('附带内容');

            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->index('transport_price_id', 'transport_price_id');
            $table->index('company_id', 'company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transport_price_operation');
    }
}
