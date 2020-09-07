<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMerchantAddColumnAdditionalStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        {
            Schema::table('merchant', function (Blueprint $table) {
                $table->tinyInteger('additional_status')->default(2)->nullable()->after('status')->comment('顺带功能1-启用2-禁用');
            });

        }    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant', function (Blueprint $table) {
            $table->dropColumn('additional_status');
        });
    }
}
