<?php
/**
 * @Author: h9471
 * @Created: 2019/10/21 17:07
 */

namespace App\Models\Scope;

use App\Models\AdditionalPackage;
use App\Models\AddressTemplate;
use App\Models\ApiTimes;
use App\Models\Batch;
use App\Models\BatchException;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\Company;
use App\Models\CompanyConfig;
use App\Models\Country;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Fee;
use App\Models\Holiday;
use App\Models\HolidayDate;
use App\Models\KilometresCharging;
use App\Models\Line;
use App\Models\LineArea;
use App\Models\LineRange;
use App\Models\Material;
use App\Models\Merchant;
use App\Models\MerchantApi;
use App\Models\MerchantGroup;
use App\Models\MerchantRecharge;
use App\Models\Order;
use App\Models\OrderNoRule;
use App\Models\OrderTrail;
use App\Models\Package;
use App\Models\RouteTracking;
use App\Models\SpecialTimeCharging;
use App\Models\Tour;
use App\Models\TourDriverEvent;
use App\Models\TourLog;
use App\Models\TourMaterial;
use App\Models\TransportPrice;
use App\Models\Warehouse;
use App\Models\WeightCharging;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Cache;
use Matrix\Operators\Addition;

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
            if (
                !($model instanceof Company)
                && (!($model instanceof ApiTimes))
                && !($model instanceof AddressTemplate)
            ) {
                $builder->whereRaw($model->getTable() . '.company_id' . ' = ' . $user->company_id);
            }
        }

        //如果是司机端
        if ($user instanceof Driver) {
            if (!($model) instanceof Company) {
                $builder->whereRaw($model->getTable() . '.company_id' . ' = ' . $user->company_id);
            }
            //车辆模型和司机无关
            if ((!($model instanceof Car))
                && (!($model instanceof AdditionalPackage))
                && (!($model instanceof MerchantApi))
                && (!($model instanceof MerchantRecharge))
                && (!($model instanceof OrderNoRule))
                && (!($model instanceof CarBrand))
                && (!($model instanceof CarModel))
                && (!($model instanceof Material))
                && (!($model instanceof Package))
                && (!($model instanceof TourMaterial))
                && (!($model instanceof Merchant))
                && (!($model instanceof TourLog))
                && (!($model instanceof Fee))
                && (!($model instanceof Line))
                && (!($model instanceof Order))
                && (!($model instanceof Country))
                && (!($model instanceof Company))
                && (!($model instanceof CompanyConfig))
            ) {
                $builder->whereRaw($model->getTable() . '.driver_id' . ' = ' . $user->id);
            }
        }

        //如果是商家端
        if ($user instanceof Merchant) {
            $builder->whereRaw($model->getTable() . '.company_id' . ' = ' . $user->company_id);
            if (!($model instanceof Batch)
                && !($model instanceof CompanyConfig)
                && !($model instanceof Tour)
                && !($model instanceof Line)
                && !($model instanceof LineRange)
                && !($model instanceof LineArea)
                && !($model instanceof TransportPrice)
                && !($model instanceof KilometresCharging)
                && !($model instanceof WeightCharging)
                && !($model instanceof SpecialTimeCharging)
                && !($model instanceof Country)
                && !($model instanceof MerchantGroup)
                && !($model instanceof Merchant)
                && (!($model instanceof TourMaterial))
                && !($model instanceof BatchException)
                && !($model instanceof Package)
                && !($model instanceof Material)
                && !($model instanceof OrderNoRule)
                && !($model instanceof Warehouse)
                && !($model instanceof OrderTrail)
                && !($model instanceof TourDriverEvent)
                && !($model instanceof RouteTracking)
                && !($model instanceof Driver)
                && !($model instanceof Holiday)
                && !($model instanceof HolidayDate)
            ) {
                $builder->whereRaw($model->getTable() . '.merchant_id' . ' = ' . $user->id);
            }
        }
    }
}
