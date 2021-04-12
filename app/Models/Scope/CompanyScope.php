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
use App\Models\Device;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Fee;
use App\Models\Holiday;
use App\Models\HolidayDate;
use App\Models\KilometresCharging;
use App\Models\Line;
use App\Models\LineArea;
use App\Models\LineRange;
use App\Models\MapConfig;
use App\Models\Material;
use App\Models\Merchant;
use App\Models\MerchantApi;
use App\Models\MerchantGroup;
use App\Models\MerchantGroupLine;
use App\Models\MerchantGroupLineRange;
use App\Models\MerchantRecharge;
use App\Models\Order;
use App\Models\OrderNoRule;
use App\Models\OrderTrail;
use App\Models\Package;
use App\Models\PackageNoRule;
use App\Models\Permission;
use App\Models\Recharge;
use App\Models\Role;
use App\Models\RouteTracking;
use App\Models\SpecialTimeCharging;
use App\Models\Stock;
use App\Models\StockException;
use App\Models\StockInLog;
use App\Models\StockOutLog;
use App\Models\Tour;
use App\Models\TourDelay;
use App\Models\TourDriverEvent;
use App\Models\TourLog;
use App\Models\TourMaterial;
use App\Models\TrackingOrder;
use App\Models\TrackingOrderMaterial;
use App\Models\TrackingOrderPackage;
use App\Models\TrackingOrderTrail;
use App\Models\TransportPrice;
use App\Models\Warehouse;
use App\Models\WeightCharging;
use App\Services\Admin\BaseLineService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Str;

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
        $query = $builder->getQuery();
        $sql = $query->toSql();
        $whereColumns = array_column($query->wheres, 'column');
        //如果是员工端
        if ($user instanceof Employee) {
            if (
                !($model instanceof Company)
                && (!($model instanceof ApiTimes))
                && !($model instanceof AddressTemplate)
                && !($model instanceof Permission)
                && (!in_array('company_id', $whereColumns))
            ) {
                $builder->whereRaw($model->getTable() . '.company_id' . '=' . $user->company_id);
            }
        }

        //如果是司机端
        if ($user instanceof Driver) {
            if (!($model) instanceof Company) {
                $builder->whereRaw($model->getTable() . '.company_id' . '=' . $user->company_id);
            }
            //车辆模型和司机无关
            if ((!($model instanceof Car))
                && (!($model instanceof Driver))
                && (!($model instanceof AdditionalPackage))
                && (!($model instanceof MerchantApi))
                && (!($model instanceof MerchantRecharge))
                && (!($model instanceof OrderNoRule))
                && (!($model instanceof CarBrand))
                && (!($model instanceof CarModel))
                && (!($model instanceof Material))
                && (!($model instanceof Package))
                && (!($model instanceof TrackingOrderMaterial))
                && (!($model instanceof TrackingOrderPackage))
                && (!($model instanceof Batch))
                && (!($model instanceof TourMaterial))
                && (!($model instanceof TourDelay))
                && (!($model instanceof Merchant))
                && (!($model instanceof TourLog))
                && (!($model instanceof Fee))
                && (!($model instanceof Warehouse))
                && (!($model instanceof Line))
                && (!($model instanceof LineRange))
                && (!($model instanceof MerchantGroup))
                && (!($model instanceof MerchantGroupLineRange))
                && (!($model instanceof LineArea))
                && (!($model instanceof TrackingOrder))
                && (!($model instanceof Order))
                && (!($model instanceof TrackingOrderTrail))
                && (!($model instanceof Country))
                && (!($model instanceof Company))
                && (!($model instanceof CompanyConfig))
                && (!($model instanceof Recharge))
                && (!($model instanceof Device))
                && (!($model instanceof Stock))
                && (!($model instanceof StockInLog))
                && (!($model instanceof StockOutLog))
                && (!($model instanceof PackageNoRule))
                && (!($model instanceof StockException))
                && (!($model instanceof MerchantGroup))
                && (!($model instanceof MapConfig))
                && (!in_array('driver_id', $whereColumns))
                && (!Str::contains($sql, "IFNULL(driver_id,0) <> -1"))
            ) {
                $builder->whereRaw($model->getTable() . '.driver_id' . '=' . $user->id);
            }
        }

        //如果是商家端
        if ($user instanceof Merchant) {
            $builder->whereRaw($model->getTable() . '.company_id' . '=' . $user->company_id);
            if (!($model instanceof Batch)
                && !($model instanceof CompanyConfig)
                && !($model instanceof BaseLineService)
                && !($model instanceof MerchantGroupLine)
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
                && !($model instanceof TrackingOrderTrail)
                && !($model instanceof TourDriverEvent)
                && !($model instanceof RouteTracking)
                && !($model instanceof Driver)
                && !($model instanceof Holiday)
                && !($model instanceof HolidayDate)
                && (!($model instanceof MerchantGroup))
                && !($model instanceof MerchantGroupLineRange)
                && (!($model instanceof MapConfig))
                && (!in_array('merchant_id', $whereColumns))
            ) {
                $builder->whereRaw($model->getTable() . '.merchant_id' . '=' . $user->id);
            }
        }
    }
}
