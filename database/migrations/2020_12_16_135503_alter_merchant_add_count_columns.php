<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMerchantAddCountColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant', function (Blueprint $table) {
            $table->tinyInteger('pickup_count')->default(1)->nullable()->after('status')->comment('取件次数0-手动');
            $table->tinyInteger('pie_count')->default(1)->nullable()->after('pickup_count')->comment('派件次数0-手动');
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
