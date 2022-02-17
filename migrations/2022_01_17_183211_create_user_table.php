<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->comment('用户');

            $table->bigIncrements('id');
            $table->string('name')->unique()->default('')->nullable()->comment('名字');
            $table->string('password')->default('')->nullable()->comment('密码');
            $table->string('key')->default('')->nullable()->comment('秘钥');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态1-允许2-禁用');

            $table->unique('name', 'name');
            $table->index('status', 'status');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
}
