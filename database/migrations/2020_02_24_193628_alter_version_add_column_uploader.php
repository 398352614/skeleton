<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVersionAddColumnUploader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('version', function (Blueprint $table) {
            $table->integer('company_id')->default(0)->nullable()->after('id')->comment('公司ID');
            $table->string('uploader_email')->default(null)->nullable()->after('company_id')->comment('上传者邮箱');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('version', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('uploader_email');
        });
    }
}
