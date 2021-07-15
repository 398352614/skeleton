<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertCarAccidentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_accident', function (Blueprint $table) {
            $table->decimal('insurance_price')->default(0)->comment('赔付金额')->after('insurance_payment');
            $table->date('insurance_date')->default(null)->nullable()->comment('赔付时间')->after('insurance_payment');
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
