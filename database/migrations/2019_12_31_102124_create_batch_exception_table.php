<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchExceptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_exception', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->default(null)->comment('公司ID');
            $table->string('batch_exception_no', 50)->default('')->nullable()->comment('异常编号');
            $table->string('batch_no', 50)->default('')->nullable()->comment('站点编号');
            $table->string('receiver', 50)->default('')->nullable()->comment('收货方姓名');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-未处理2-已处理');
            $table->string('source', 50)->default('')->nullable()->comment('来源');
            $table->tinyInteger('stage')->default(1)->nullable()->comment('异常阶段1-在途异常2-装货异常');
            $table->tinyInteger('type')->default(1)->nullable()->comment('异常类型');
            $table->string('remark', 250)->default('')->nullable()->comment('异常内容');
            $table->string('picture', 250)->default('')->nullable()->comment('异常图片');
            $table->string('deal_remark', 250)->default('')->nullable()->comment('处理内容');
            $table->integer('deal_id')->default(null)->nullable()->comment('处理人ID(员工ID)');
            $table->string('deal_name', 50)->default('')->nullable()->comment('处理人姓名');
            $table->dateTime('deal_time')->default(null)->nullable()->comment('处理时间');
            $table->integer('driver_id')->default(null)->nullable()->comment('司机ID(创建人ID)');
            $table->string('driver_name', 50)->default('')->nullable()->comment('司机姓名(创建人姓名)');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_exception');
    }
}
