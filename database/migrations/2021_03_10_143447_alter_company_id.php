<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompanyId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('route_retry', function (Blueprint $table) {
            $table->integer('company_id')->default(0)->nullable(false)->comment('公司ID')->change();
        });
        Schema::table('institutions', function (Blueprint $table) {
            $table->integer('company_id')->default(0)->nullable(false)->comment('公司ID')->change();
        });
        Schema::table('line_log', function (Blueprint $table) {
            $table->integer('company_id')->default(0)->nullable(false)->comment('公司ID')->change();
        });
        Schema::table('transport_price_operation', function (Blueprint $table) {
            $table->integer('company_id')->default(0)->nullable(false)->comment('公司ID')->change();
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
