<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBatchAddIdentityColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch', function (Blueprint $table) {
            $table->string('auth_fullname', 100)->default('')->nullable()->after('pay_picture')->comment('身份人姓名');
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
        Schema::table('batch', function (Blueprint $table) {
            $table->dropColumn('auth_fullname');
            $table->dropColumn('auth_birth_date');
        });
    }
}
