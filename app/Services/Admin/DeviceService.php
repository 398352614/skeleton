<?php
/**
 * 设备服务
 * User: long
 * Date: 2020/6/22
 * Time: 13:46
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\DeviceResource;
use App\Models\Device;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class DeviceService extends BaseService
{
    public $filterRules = [
        'number' => ['like', 'keyword'],
        'driver_id' => ['=', 'driver_id']
    ];

    public function __construct(Device $model)
    {
        parent::__construct($model, DeviceResource::class);
    }

    /**
     * 取件线路 服务
     * @return TourService
     */
    private function getTourService()
    {
        return self::getInstance(TourService::class);
    }

    /**
     * 获取详情
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $device = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($device)) {
            throw new BusinessLogicException('数据不存在');
        }
        $device = $device->toArray();
        unset($device['driver']);
        return $device;
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败');
        }
    }


    /**
     * 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $rowCount = parent::updateById($id, Arr::only($data, ['number', 'mode']));
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }

    /**
     * 绑定
     * @param $id
     * @param $driverId
     * @throws BusinessLogicException
     */
    public function bind($id, $driverId)
    {
        $device = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($device)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (!empty($device->driver_id)) {
            throw new BusinessLogicException('该设备已绑定司机[:driver_name]', 1000, ['driver_name' => $device->driver_id_name]);
        }
        $rowCount = parent::updateById($id, ['driver_id' => $driverId]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }

    /**
     * 解绑
     * @param $id
     * @throws BusinessLogicException
     */
    public function unBind($id)
    {
        $rowCount = parent::updateById($id, ['driver_id' => null]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $device = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($device)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (!empty($device->driver_id)) {
            $tour = $this->getTourService()->getInfo(['driver_id' => $device->driver_id, 'status' => ['<>', BaseConstService::TOUR_STATUS_5]], 'id', false);
            if (!empty($tour)) {
                throw new BusinessLogicException('正在进行线路任务，请先解绑设备');
            }
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }


}
