<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('机构组织名');
            $table->string('phone')->nullable()->comment('机构组织电话');
            $table->string('contacts')->nullable()->comment('机构组织负责人');
            $table->string('country')->nullable()->comment('机构组织国家城市');
            $table->string('address')->nullable()->comment('机构组织负责人详细地址');
            $table->bigInteger('parent_id')->comment('父ID');
            $table->bigInteger('company_id');
            $table->timestamps();
            $table->index(['company_id']);
            $table->index(['parent_id']);
        });

        Schema::create('institutions_closure', function (Blueprint $table) {
            $table->unsignedInteger('ancestor');
            $table->unsignedInteger('descendant');
            $table->unsignedTinyInteger('distance');
            $table->primary(['ancestor', 'descendant']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institutions');
        Schema::dropIfExists('institutions_closure');
    }
}
