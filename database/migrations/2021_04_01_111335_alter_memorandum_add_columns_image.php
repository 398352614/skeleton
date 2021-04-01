<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMemorandumAddColumnsImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memorandum', function (Blueprint $table) {
            $table->text('image_list')->default(null)->nullable()->comment('图片列表')->after('content');
            $table->date('create_date')->default(null)->nullable()->comment('创建日期')->after('image_list');
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
