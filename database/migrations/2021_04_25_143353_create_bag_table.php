<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bag', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('bag_no', 50)->default('')->nullable()->comment('袋号');
            $table->string('shift_no', 50)->default('')->nullable()->comment('车次号');
            $table->tinyInteger('status')->default(1)->comment('状态:1-未发车2-已发车3-已到车4-未拆袋5-已拆袋');
            $table->decimal('weight', 2)->default(0.00)->nullable()->comment('包裹总重量');
            $table->integer('package_count')->default(0)->nullable()->comment('包裹数量');

            $table->integer('warehouse_id')->default(null)->nullable()->comment('所在网点ID');
            $table->string('warehouse_name')->default('')->nullable()->comment('所在网点名称');
            $table->integer('next_warehouse_id')->default(null)->nullable()->comment('目的地网点ID');
            $table->string('next_warehouse_name')->default('')->nullable()->comment('目的地网点名称');

            $table->dateTime('load_time')->default(null)->nullable()->comment('装车时间');
            $table->string('load_operator')->default('')->nullable()->comment('装车操作人');
            $table->integer('load_operator_id')->default(null)->nullable()->comment('装车操作人ID');
            $table->dateTime('unload_time')->default(null)->nullable()->comment('卸车时间');
            $table->string('unload_operator')->default('')->nullable()->comment('卸车操作人');
            $table->integer('unload_operator_id')->default(null)->nullable()->comment('卸车操作人ID');
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
