<?php
/**
 * 设备 服务
 * User: long
 * Date: 2020/6/28
 * Time: 15:50
 */

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Models\Device;

class DeviceService extends BaseService
{
    public function __construct(Device $model, $resource = null, $infoResource = null)
    {
        parent::__construct($model, $resource, $infoResource);
    }

    public function show()
    {
        $device = parent::getInfo(['driver_id' => auth()->user()->id], ['*'], false);
        if (empty($device)) return [];
        $device = $device->toArray();
        unset($device['driver']);
        return $device;
    }

    /**
     * 绑定
     * @param $params
     * @return string
     * @throws BusinessLogicException
     */
    public function bind($params)
    {
        //1.如果设备不存在,则新增设备
        $device = parent::getInfo(['number' => $params['number']], ['*'], false);
        if (empty($device)) {
            $rowCount = parent::create(['number' => $params['number'], 'driver_id' => auth()->user()->id]);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败，请重新操作');
            }
            return 'true';
        }
        //2.如果设备存在,则绑定
        if (!empty($device->driver_id)) {
            throw new BusinessLogicException('该设备已绑定司机[:driver_name]', 1000, ['driver_name' => $device->driver_id_name]);
        }
        $rowCount = parent::updateById($device->id, ['driver_id' => auth()->user()->id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        return 'true';
    }

    /**
     * 解绑
     * @throws BusinessLogicException
     */
    public function unBind()
    {
        $rowCount = parent::update(['driver_id' => auth()->user()->id], ['driver_id' => null]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }
}
