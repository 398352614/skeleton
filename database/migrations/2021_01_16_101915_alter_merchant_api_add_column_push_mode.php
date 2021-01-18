<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMerchantApiAddColumnPushMode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_api', function (Blueprint $table) {
            $table->tinyInteger('push_mode')->default(1)->nullable()->after('status')->comment('推送方式：1-老模式2-详情模式3-简略模式4-自定义模式');
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
