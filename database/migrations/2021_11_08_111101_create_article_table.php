<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->tinyInteger('type')->default(1)->nullable()->comment('类型');
            $table->text('text')->default('')->nullable()->comment('正文');
            $table->string('tittle')->default('')->nullable()->comment('标题');
            $table->integer('operator_id')->default(null)->nullable()->comment('操作人ID');
            $table->string('operator_name')->default('')->nullable()->comment('操作人名称');
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
        Schema::dropIfExists('article');
    }
}
