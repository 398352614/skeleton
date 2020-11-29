<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/11/29
 * Time: 16:06
 */

namespace App\Services\Driver;


use App\Exceptions\BusinessLogicException;
use App\Models\Stock;
use App\Services\BaseConstService;
use Illuminate\Database\Eloquent\Model;

class StockService extends BaseService
{
    public function __construct(Stock $stock, $resource = null, $infoResource = null)
    {
        parent::__construct($stock, $resource, $infoResource);
    }

    /**
     * 分拣入库
     * @param $package
     * @param $tour
     * @param $trackingOrder
     * @throws BusinessLogicException
     */
    public function pickOut($package, $tour, $trackingOrder)
    {
        $dbPackage = parent::getInfoLock(['express_first_no' => $package['express_first_no']], ['*'], false);
        //若存在包裹,则放入日志中，并且删除
        if (!empty($dbPackage)) {
            $rowCount = parent::delete(['express_first_no' => $dbPackage->express_first_no]);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败，请重新操作');
            }
            $stockLogData = array_merge($dbPackage->toArray(), ['type' => BaseConstService::WAREHOUSE_PACKAGE_TYPE_1]);
            $this->getStockLogService()->create($stockLogData);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败，请重新操作');
            }
        }
        //加入库存
        $stockData = [
            'line_id' => $tour['line_id'],
            'line_name' => $tour['line_name'],
            'tracking_order_no' => $trackingOrder['tracking_order_no'],
            'execution_date' => $tour['execution_date'],
            'operator' => auth()->user()->fullname,
            'order_no' => $package['order_no'],
            'in_warehouse_time' => now(),
            'express_first_no' => $package['express_first_no']
        ];
        $rowCount = parent::create($stockData);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }


}