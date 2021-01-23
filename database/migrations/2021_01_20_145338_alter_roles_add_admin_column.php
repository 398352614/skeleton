<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRolesAddAdminColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->tinyInteger('is_admin')->default(2)->nullable()->after('name')->comment('是否是管理员组1-是2-否');
        });

        Schema::table('employee', function (Blueprint $table) {
            $table->tinyInteger('is_admin')->default(2)->nullable()->after('forbid_login')->comment('是否是管理员1-是2-否');
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
