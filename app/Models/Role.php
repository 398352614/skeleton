<?php

namespace App\Models;

use App\Exceptions\BusinessLogicException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\RefreshesPermissionCache;

/**
 * 角色表
 * Class Car
 * @package App\Models
 */
class Role extends BaseModel implements RoleContract
{
    use HasPermissions;
    use RefreshesPermissionCache;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'is_admin',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    protected $appends = [

    ];

    protected $guarded = ['id'];

    /**
     * @param \Spatie\Permission\Contracts\Permission|\Spatie\Permission\Contracts\Role $roleOrPermission
     *
     * @throws \Spatie\Permission\Exceptions\GuardDoesNotMatch
     */
    protected function ensureModelSharesGuard($roleOrPermission)
    {
        //不判断守卫
    }

    /**
     * 创建
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws BusinessLogicException
     */
    public static function create(array $attributes = [])
    {
        empty($attributes['company_id']) && $attributes['company_id'] = auth()->user()->company_id;
        $role = static::where('name', $attributes['name'])->where('company_id', $attributes['company_id'])->first();
        if (!empty($role)) {
            throw new BusinessLogicException('权限组已存在');
        }
        return static::query()->create($attributes);
    }

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            'role_id',
            'permission_id'
        );
    }

    /**
     * A permission can be applied to roles.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions'),
            'permission_id',
            'role_id'
        );
    }


    /**
     * Find a role by its name and guard name.
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Role|\Spatie\Permission\Models\Role
     *
     * @throws \Spatie\Permission\Exceptions\RoleDoesNotExist
     * @throws BusinessLogicException
     */
    public static function findByName(string $name, $guardName = null): RoleContract
    {
        $role = static::where('name', $name)->where('company_id', auth()->user()->company_id)->first();
        if (empty($role)) {
            throw new BusinessLogicException('权限组不存在');
        }

        return $role;
    }

    /**
     * Find or create role by its name (and optionally guardName).
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Role
     * @throws BusinessLogicException
     */
    public static function findOrCreate(string $name, $guardName = null): RoleContract
    {
        $role = static::where('name', $name)->where('company_id', auth()->user()->company_id)->first();

        if (empty($role)) {
            throw new BusinessLogicException('权限组不存在');
        }

        return $role;
    }

    /**
     * @param int $id
     * @param null $guardName
     * @return \Spatie\Permission\Contracts\Role|\Spatie\Permission\Models\Role
     * @throws BusinessLogicException
     */
    public static function findById(int $id, $guardName = null): RoleContract
    {
        $role = static::where('id', $id)->where('company_id', auth()->user()->company_id)->first();
        if (empty($role)) {
            throw new BusinessLogicException('权限组不存在');
        }
        return $role;
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param string|Permission $permission
     *
     * @return bool
     *
     * @throws \Spatie\Permission\Exceptions\GuardDoesNotMatch
     * @throws BusinessLogicException
     */
    public function hasPermissionTo($permission): bool
    {
        if (config('permission.enable_wildcard_permission', false)) {
            return $this->hasWildcardPermission($permission, $this->getDefaultGuardName());
        }

        /**@var \App\Models\Permission $permissionClass */
        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findByName($permission, $this->getDefaultGuardName());
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById($permission, $this->getDefaultGuardName());
        }

        return $this->permissions->contains('id', $permission->id);
    }
}

