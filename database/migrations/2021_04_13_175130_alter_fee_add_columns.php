<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFeeAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee', function (Blueprint $table) {
            $table->tinyInteger('is_valuable')->default(1)->nullable()->after('status')->comment('是否计费1-计费2-不计费');
            $table->tinyInteger('payer')->default(1)->nullable()->after('is_valuable')->comment('支付方1-货主2-客户');
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
