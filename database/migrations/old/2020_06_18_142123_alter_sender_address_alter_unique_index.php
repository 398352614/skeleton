<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSenderAddressAlterUniqueIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sender_address',function (Blueprint $table){
            $table->dropIndex('all_address');
            $table->unique(['merchant_id', 'sender_country', 'sender_fullname', 'sender_phone', 'sender_post_code', 'sender_house_number', 'sender_city', 'sender_street', 'sender_address'],'all_address');
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
