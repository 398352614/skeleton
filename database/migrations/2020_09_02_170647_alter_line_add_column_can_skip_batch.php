<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLineAddColumnCanSkipBatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('line', function (Blueprint $table) {
            $table->tinyInteger('can_skip_batch')->default(2)->nullable()->after('is_increment')->comment('站点能否跳过1-不能2-可以');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('line', function (Blueprint $table) {
            $table->dropColumn('can_skip_batch');
        });
    }
}
