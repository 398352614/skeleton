<?php

namespace App\Models;

use App\Exceptions\BusinessLogicException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\RefreshesPermissionCache;

/**
 * 权限表
 * Class Car
 * @package App\Models
 */
class Permission extends BaseModel implements PermissionContract
{
    use HasRoles;
    use RefreshesPermissionCache;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

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
        'parent_id',
        'name',
        'route',
        'route_as',
        'level',
        'type',
        'is_show',
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
        $permission = static::getPermissions(['route_as' => $attributes['route_as']])->first();
        if (!empty($permission)) {
            throw new BusinessLogicException('路由已存在');
        }
        return static::query()->create($attributes);
    }

    /**
     * A permission can be applied to roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions'),
            'permission_id',
            'role_id'
        )->where('company_id', '<>', null);
    }

    /**
     * A permission belongs to some users of the model associated with its guard.
     */
    public function users(): MorphToMany
    {
        return $this->morphedByMany(
            Employee::class,
            'model',
            config('permission.table_names.model_has_permissions'),
            'permission_id',
            config('permission.column_names.model_morph_key')
        )->where('company_id', '<>', null);
    }

    /**
     * Find a permission by its name (and optionally guardName).
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Permission
     * @throws \Spatie\Permission\Exceptions\PermissionDoesNotExist
     * @throws BusinessLogicException
     *
     */
    public static function findByName(string $name, $guardName = null): PermissionContract
    {
        $permission = static::getPermissions(['route_as' => $name])->first();
        if (empty($permission)) {
            throw new BusinessLogicException('当前权限不存在');
        }

        return $permission;
    }


    /**
     * Find a permission by its id (and optionally guardName).
     *
     * @param int $id
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Permission
     * @throws \Spatie\Permission\Exceptions\PermissionDoesNotExist
     * @throws BusinessLogicException
     *
     */
    public static function findById(int $id, $guardName = null): PermissionContract
    {
        $permission = static::getPermissions(['id' => $id])->first();

        if (empty($permission)) {
            throw new BusinessLogicException('当前权限不存在');
        }

        return $permission;
    }

    /**
     * Find or create permission by its name (and optionally guardName).
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Permission
     */
    public static function findOrCreate(string $name, $guardName = null): PermissionContract
    {
        $permission = static::getPermissions(['name' => $name])->first();

        return $permission;
    }

    /**
     * Get the current cached permissions.(cache is too big,suo can not)
     */
    protected static function getPermissions(array $params = []): Collection
    {
//        return app(PermissionRegistrar::class)
//            ->setPermissionClass(static::class)
//            ->getPermissions($params);
        return self::query()->select([
            'id', 'parent_id', 'name', 'route_as', 'type'
        ])->when(!empty($params), function ($query) use ($params) {
            foreach ($params as $attr => $value) {
                $query = $query->where($attr, $value);
            }
        })->with('roles:id,company_id,name,is_admin')->get();
    }
}

