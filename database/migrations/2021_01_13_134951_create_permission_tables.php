<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding.');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('parent_id')->default(null)->nullable()->comment('父级ID');
            $table->string('name', 50)->default('')->nullable()->comment('名称');
            $table->string('route', 150)->default('')->nullable()->comment('路由');
            $table->string('route_as', 150)->default('')->nullable()->comment('路由别名');
            $table->tinyInteger('level')->default(1)->nullable()->comment('等级1,2,3,4,5');
            $table->tinyInteger('sort_id')->default(1)->nullable()->comment('排序');
            $table->tinyInteger('type')->default(1)->nullable()->comment('1-菜单2-按钮');
            $table->tinyInteger('is_show')->default(2)->nullable()->comment('是否显示1-是2-否');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->index('route', 'route');
            $table->index('route_as', 'route_as');
            $table->index('parent_id', 'parent_id');
            $table->index('type', 'type');
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
            $table->string('name', 50)->default('')->nullable()->comment('名称');
            $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');

            $table->unique(['company_id', 'name'], 'company_id_name');
            $table->index('company_id', 'company_id');

        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger($columnNames['model_morph_key'])->comment('用户ID');
            $table->unsignedInteger('permission_id')->comment('权限ID');
            $table->string('model_type', 150);

            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');
            $table->foreign('permission_id')->references('id')->on($tableNames['permissions'])->onDelete('cascade');
            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_permission_model_type_primary');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('role_id')->comment('角色ID');
            $table->unsignedInteger($columnNames['model_morph_key'])->comment('用户ID');
            $table->string('model_type', 150);

            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');
            $table->foreign('role_id')->references('id')->on($tableNames['roles'])->onDelete('cascade');
            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'], 'model_has_roles_role_model_type_primary');
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('role_id')->comment('角色ID');
            $table->unsignedInteger('permission_id')->comment('权限ID');

            $table->foreign('permission_id')->references('id')->on($tableNames['permissions'])->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on($tableNames['roles'])->onDelete('cascade');
            $table->primary(['permission_id', 'role_id'], 'permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
