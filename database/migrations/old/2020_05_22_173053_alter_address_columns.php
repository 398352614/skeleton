<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddressColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->renameColumn('receiver', 'receiver_fullname');
            $table->renameColumn('sender', 'sender_fullname');
        });
        Schema::table('batch', function (Blueprint $table) {
            $table->renameColumn('receiver', 'receiver_fullname');
        });
        Schema::table('receiver_address', function (Blueprint $table) {
            $table->dropIndex('all_address');
            $table->renameColumn('receiver', 'receiver_fullname');
            $table->unique(['merchant_id', 'receiver_fullname', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street'], 'all_address');
        });
        Schema::table('sender_address', function (Blueprint $table) {
            $table->dropIndex('all_address');
            $table->renameColumn('sender', 'sender_fullname');
            $table->unique(['merchant_id', 'sender_fullname', 'sender_phone', 'sender_country', 'sender_post_code', 'sender_house_number', 'sender_city', 'sender_street'], 'all_address');
        });
        Schema::table('warehouse', function (Blueprint $table) {
            $table->renameColumn('contacter', 'fullname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->renameColumn('receiver_fullname', 'receiver');
            $table->renameColumn('sender_fullname', 'sender');
        });
        Schema::table('batch', function (Blueprint $table) {
            $table->renameColumn('receiver_fullname', 'receiver');
        });
        Schema::table('receiver_address', function (Blueprint $table) {
            $table->dropIndex('all_address');
            $table->renameColumn('receiver_fullname', 'receiver');
            $table->unique(['merchant_id', 'receiver', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street'], 'all_address');
        });
        Schema::table('sender_address', function (Blueprint $table) {
            $table->dropIndex('all_address');
            $table->renameColumn('sender_fullname', 'sender');
            $table->unique(['merchant_id', 'sender', 'sender_phone', 'sender_country', 'sender_post_code', 'sender_house_number', 'sender_city', 'sender_street'], 'all_address');
        });
        Schema::table('warehouse', function (Blueprint $table) {
            $table->renameColumn('fullname', 'contacter');
        });
    }
}
