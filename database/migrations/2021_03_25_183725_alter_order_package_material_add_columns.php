<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderPackageMaterialAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->tinyInteger('control_mode')->default(1)->comment('控货方式')->after('list_mode');
            $table->tinyInteger('transport_mode')->default(1)->comment('运输方式')->after('control_mode');
            $table->tinyInteger('origin_type')->default(1)->comment('始发地')->after('transport_mode');
        });
        Schema::table('package', function (Blueprint $table) {
            $table->tinyInteger('size')->default(1)->comment('体积')->after('weight');
            $table->tinyInteger('feature')->default(1)->comment('特性')->after('feature_logo');
        });
        Schema::table('material', function (Blueprint $table) {
            $table->tinyInteger('pack_type')->default(1)->comment('包装')->after('actual_quantity');
            $table->tinyInteger('type')->default(1)->comment('类型')->after('pack_type');
            $table->decimal('weight')->default(1)->comment('重量')->after('type');
            $table->decimal('size')->default(1)->comment('体积')->after('weight');
            $table->decimal('unit_price')->default(1)->comment('单价')->after('size');

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
