<?php
/**
 * @Author: h9471
 * @Created: 2019/10/21 17:07
 */

namespace App\Models\Scope;

use App\Models\Driver;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Cache;

class CompanyScope implements Scope
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {

        $user = auth()->user();

        //如果是员工端
        if ($user instanceof Employee) {
            $builder->whereRaw($model->getTable() . '.company_id' . ' = ' . $user->company_id);
        }

        //如果是司机端
        if ($user instanceof Driver) {
            $builder->whereRaw($model->getTable() . '.company_id' . ' = ' . $user->company_id);
            $builder->whereRaw($model->getTable() . '.driver_id' . ' = ' . $user->id);
        }
    }
}
