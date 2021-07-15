<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablesAddDistrictProvince extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address', function (Blueprint $table) {
            $table->string('place_province')->default('')->comment('收件人省份')->after('place_country');
            $table->string('place_district')->default('')->comment('收件人区县')->after('place_city');
        });
        Schema::table('warehouse', function (Blueprint $table) {
            $table->string('province')->default('')->comment('省份')->after('country');
            $table->string('district')->default('')->comment('区县')->after('city');
        });
        Schema::table('order', function (Blueprint $table) {
            $table->string('place_province')->default('')->comment('收件人省份')->after('place_country');
            $table->string('place_district')->default('')->comment('收件人区县')->after('place_city');
            $table->string('second_place_province')->default('')->comment('发件人省份')->after('second_place_country');
            $table->string('second_place_district')->default('')->comment('发件人区县')->after('second_place_city');
        });
        Schema::table('batch', function (Blueprint $table) {
            $table->string('place_province')->default('')->comment('网点省份')->after('place_country');
            $table->string('place_district')->default('')->comment('网点区县')->after('place_city');
        });
        Schema::table('tour', function (Blueprint $table) {
            $table->string('warehouse_province')->default('')->comment('收件人省份')->after('warehouse_country');
            $table->string('warehouse_district')->default('')->comment('收件人区县')->after('warehouse_city');
        });
        Schema::table('tracking_order', function (Blueprint $table) {
            $table->string('place_province')->default('')->comment('收件人省份')->after('place_country');
            $table->string('place_district')->default('')->comment('收件人区县')->after('place_city');
            $table->string('warehouse_province')->default('')->comment('网点省份')->after('warehouse_country');
            $table->string('warehouse_district')->default('')->comment('网点区县')->after('warehouse_city');
        });
        Schema::table('additional_package', function (Blueprint $table) {
            $table->string('place_province')->default('')->comment('网点省份')->after('place_country');
            $table->string('place_district')->default('')->comment('网点区县')->after('place_city');
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
