<?php
/**
 * @Author: h9471
 * @Created: 2019/10/21 17:07
 */

namespace App\Models\Scope;

use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Material;
use App\Models\Merchant;
use App\Models\OrderNoRule;
use App\Models\Package;
use App\Models\TourMaterial;
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
            if (!($model instanceof Company)) {
                $builder->whereRaw($model->getTable() . '.company_id' . ' = ' . $user->company_id);
            }
        }

        //如果是司机端
        if ($user instanceof Driver) {
            $builder->whereRaw($model->getTable() . '.company_id' . ' = ' . $user->company_id);
            //车辆模型和司机无关
            if ((!($model instanceof Car))
                && (!($model instanceof OrderNoRule))
                && (!($model instanceof CarBrand))
                && (!($model instanceof CarModel))
                && (!($model instanceof Material))
                && (!($model instanceof Package))
                && (!($model instanceof TourMaterial))
            ) {
                $builder->whereRaw($model->getTable() . '.driver_id' . ' = ' . $user->id);
            }
        }

        //如果是商家端
        if ($user instanceof Merchant) {
            $builder->whereRaw($model->getTable() . '.company_id' . ' = ' . $user->company_id);
            //$builder->whereRaw($model->getTable() . '.merchant_id' . ' = ' . $user->merchant_id);
        }
    }
}
