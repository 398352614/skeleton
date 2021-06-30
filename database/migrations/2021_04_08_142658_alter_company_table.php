<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company', function (Blueprint $table) {
            $table->string('web_site')->nullable()->comment('公司网址');
            $table->string('system_name')->nullable()->comment('系统名称');
            $table->string('logo_url')->nullable()->comment('公司Logo');
            $table->string('login_logo_url')->nullable()->comment('登录页Logo');
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
