<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $currentDir = '/database/migrations/create/';
        $dir = __DIR__ . DIRECTORY_SEPARATOR . 'create';
        if (!is_dir($dir)) return;
        $filePathList = [];
        foreach (scandir($dir) as $file) {
            if ($file == '.' || $file == '..') continue;
            $filePathList[] = $currentDir . $file;
        }
        if (empty($filePathList)) return;
        foreach ($filePathList as $file) {
            \Illuminate\Support\Facades\Artisan::call('migrate --path=' . "{$file}");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
