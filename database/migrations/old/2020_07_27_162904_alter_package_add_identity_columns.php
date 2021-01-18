<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPackageAddIdentityColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->tinyInteger('is_auth')->default(2)->nullable()->after('remark')->comment('是否需要身份验证1-是2-否');
            $table->string('auth_fullname', 100)->default('')->nullable()->after('is_auth')->comment('身份人姓名');
            $table->date('auth_birth_date')->default(null)->nullable()->after('auth_fullname')->comment('身份人出身年月');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->dropColumn('is_auth');
            $table->dropColumn('auth_fullname');
            $table->dropColumn('auth_birth_date');
        });
    }
}
