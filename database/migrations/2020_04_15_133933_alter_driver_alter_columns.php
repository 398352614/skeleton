<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDriverAlterColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->dropColumn('first_name');
            $table->dropColumn('post_code');
            $table->dropColumn('door_no');
            $table->dropColumn('street');
            $table->dropColumn('city');
            $table->string('fullname', 50)->default('')->nullable()->after('password')->comment('姓名');
            $table->string('address', 250)->default('')->nullable()->after('duty_paragraph')->comment('税号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('driver', function (Blueprint $table) {
            $table->dropColumn('fullname');
            $table->dropColumn('address');
            $table->string('last_name', 50)->default('')->nullable()->after('password')->comment('名');
            $table->string('first_name', 50)->default('')->nullable()->after('last_name')->comment('姓');
            $table->string('post_code', 50)->default('')->nullable()->after('duty_paragraph')->comment('邮编');
            $table->string('door_no', 50)->default('')->nullable()->after('post_code')->comment('门牌号');
            $table->string('street', 100)->default('')->nullable()->after('door_no')->comment('街道');
            $table->string('city', 100)->default('')->nullable()->after('street')->comment('城市');
        });
    }
}
