<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDriverAddColumnWarehouseName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver', function (Blueprint $table) {
            $table->string('warehouse_name')->default(null)->nullable()->after('warehouse_id')->comment('网点名称');
            $table->index('warehouse_id','warehouse_id');
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
