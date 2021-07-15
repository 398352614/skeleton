<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablesStockAddWarehouseId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock', function (Blueprint $table) {
            $table->integer('warehouse_id')->default(null)->nullable()->after('company_id')->comment('网点ID');
        });
        Schema::table('stock_in_log', function (Blueprint $table) {
            $table->integer('warehouse_id')->default(null)->nullable()->after('company_id')->comment('网点ID');
        });
        Schema::table('stock_out_log', function (Blueprint $table) {
            $table->integer('warehouse_id')->default(null)->nullable()->after('company_id')->comment('网点ID');
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
