<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPackageAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->smallInteger('expect_quantity')->default(1)->nullable()->after('weight')->comment('预计数量');
            $table->smallInteger('actual_quantity')->default(0)->nullable()->after('expect_quantity')->comment('实际数量');
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
            $table->smallInteger('quantity')->default(1)->nullable()->after('weight')->comment('数量');
            $table->dropColumn('expect_quantity');
            $table->dropColumn('actual_quantity');
        });
    }
}
