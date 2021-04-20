<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterWarehouseAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse', function (Blueprint $table) {
            $table->tinyInteger('type')->default(1)->nullable()->after('name')->comment('类型1-加盟2-自营');
            $table->tinyInteger('is_center')->default(2)->nullable()->after('type')->comment('是否为分拨中心1-是2-否');
            $table->string('acceptance_type')->default(null)->nullable()->after('is_center')->comment('承接类型列表1-取件2-派件3-直送');
            $table->text('line_ids')->default('')->nullable()->after('acceptance_type')->comment('线路ID列表');
            $table->string('company_name')->default('')->nullable()->after('fullname')->comment('公司');
            $table->string('email')->default('')->nullable()->after('company_name')->comment('邮箱');
            $table->string('avatar')->default('')->nullable()->after('email')->comment('头像');
        });
        Schema::table('employee', function (Blueprint $table) {
            $table->integer('warehouse_id')->default(null)->nullable()->after('username')->comment('网点ID');
        });
        Schema::table('merchant', function (Blueprint $table) {
            $table->integer('below_warehouse')->default(null)->nullable()->after('name')->comment('是否签约网点1-是2-否');
            $table->integer('warehouse_id')->default(null)->nullable()->after('below_warehouse')->comment('网点ID');
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
