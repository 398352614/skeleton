<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTourAlterColumnsDataType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour', function (Blueprint $table) {
            $table->decimal('order_amount',16,2)->default(0.00)->nullable()->after('actual_pie_quantity')->comment('贴单费用')->change();
            $table->decimal('replace_amount',16,2)->default(0.00)->nullable()->after('order_amount')->comment('代收货款')->change();
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
