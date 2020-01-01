<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBatchAddColumnSettlementAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch', function (Blueprint $table) {
            $table->dropColumn('exception_type');
            $table->dropColumn('exception_remark');
            $table->dropColumn('exception_picture');
            $table->decimal('settlement_amount', 16, 2)->default(0.00)->nullable()->after('replace_amount')->comment('结算金额-运费');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch', function (Blueprint $table) {
            $table->dropColumn('settlement_amount');
            $table->tinyInteger('exception_type')->default(1)->nullable()->after('status')->comment('异常类型1-正常2-在途异常3-装货异常');
            $table->string('exception_remark', 250)->default('')->nullable()->after('exception_type')->comment('异常备注');
            $table->string('exception_picture', 250)->default('')->nullable()->after('exception_remark')->comment('异常图片');
        });
    }
}
