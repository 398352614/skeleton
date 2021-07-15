<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderAddColumnTotalAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->decimal('expect_total_amount', 16, 2)->default(0.00)->nullable()->comment('预计总费用')->after('delivery_amount');
            $table->decimal('actual_total_amount', 16, 2)->default(0.00)->nullable()->comment('实际总费用')->after('expect_total_amount');
        });    }

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
