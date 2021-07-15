<?php
/**
 * 入库日志 服务
 * User: long
 * Date: 2020/6/22
 * Time: 13:46
 */

namespace App\Services\Admin;

use App\Http\Resources\Api\Admin\StockInResource;
use App\Models\StockInLog;
use Illuminate\Support\Carbon;

class StockInLogService extends BaseService
{
    public $filterRules = [
        'tracking_order_no,order_no,express_first_no' => ['like', 'keyword'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'express_first_no' => ['like', 'express_first_no'],
        'order_no' => ['like', 'order_no'],
        'line_id' => ['=', 'line_id'],
        'line_name' => ['like', 'line_name'],
        'warehouse_id' => ['=', 'warehouse_id']
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(StockInLog $stockInLog)
    {
        parent::__construct($stockInLog, StockInResource::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPageList()
    {
        $data = parent::getPageList();
        $warehouseList = $this->getWareHouseService()->getList(['id' => ['in', $data->pluck('warehouse_id')->toArray()]], ['*'], false)->keyBy('id');
        $packageList = $this->getPackageService()->getList(['express_first_no' => ['in', $data->pluck('express_first_no')->toArray()]], ['*'], false)->keyBy('express_first_no');
        foreach ($data as $k => $v) {
            $data[$k]['warehouse_name'] = $warehouseList[$v['warehouse_id']]['name'] ?? '';
            $data[$k]['weight'] = $packageList[$v['express_first_no']]['weight'] ?? '';
            $data[$k]['create_date'] = Carbon::create($v['created_at'])->format('Y-m-d') ?? '';
        }
        return $data;
    }
}
