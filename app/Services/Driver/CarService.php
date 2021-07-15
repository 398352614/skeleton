<?php
/**
 * 车辆管理 服务
 * User: long
 * Date: 2019/12/30
 * Time: 14:00
 */

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\CarResource;
use App\Models\Car;
use App\Models\Tour;
use App\Services\BaseConstService;
use Illuminate\Support\Facades\DB;

class CarService extends BaseService
{
    public function __construct(Car $car)
    {
        parent::__construct($car, CarResource::class);
    }


    /**
     * 获取可选车次
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws BusinessLogicException
     */
    public function getPageList()
    {
        if (!empty($this->formData['tour_no'])) {
            $tour = Tour::query()->where('tour_no', $this->formData['tour_no'])->first();
            if (empty($tour)) {
                throw new BusinessLogicException('数据不存在');
            }
            $tourCarIdList = DB::table('tour')
                ->where('company_id', auth()->user()->company_id)
                ->where('driver_id', '<>', null)
                ->where('car_id', '<>', null)
                ->where('execution_date', $tour['execution_date'])
                ->where('status', '<>', BaseConstService::TOUR_STATUS_5)
                ->pluck('car_id')->toArray();
        }else{
            $tourCarIdList = DB::table('tour')
                ->where('company_id', auth()->user()->company_id)
                ->where('driver_id', '<>', null)
                ->where('car_id', '<>', null)
                ->where('execution_date', today()->format('Y-m-d'))
                ->where('status', '<>', BaseConstService::TOUR_STATUS_5)
                ->pluck('car_id')->toArray();
        }
        $shiftCarIdList = DB::table('shift')
            ->where('company_id', auth()->user()->company_id)
            ->where('driver_id', '<>', null)
            ->where('car_id', '<>', null)
            ->where('status', '<>', BaseConstService::SHIFT_STATUS_4)
            ->pluck('car_id')->toArray();
        $carIdList = array_merge($tourCarIdList, $shiftCarIdList);
        if (!empty($carIdList)) {
            $this->query->whereNotIn('id', $carIdList);
        }
        $this->query->where('is_locked', '=', BaseConstService::DRIVER_TO_NORMAL);
        return parent::getPageList();
    }
}
