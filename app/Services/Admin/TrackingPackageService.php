<?php


namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\BagResource;
use App\Models\Bag;
use App\Models\TrackingPackage;
use App\Services\Admin\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TrackingPackageService extends BaseService
{
    public $filterRules = [
        'status' => ['=', 'status'],
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(TrackingPackage $model)
    {
        parent::__construct($model);
    }

    /**
     * 通过订单批量新增转运单
     * @param $order
     * @param $warehouseId
     * @throws BusinessLogicException
     */
    public function storeByOrder($order, $warehouseId)
    {
        $packageList = $this->getPackageService()->getList(['order_no' => $order['order_no']], ['*'], false);
        foreach ($packageList as $k => $v) {
            $this->getStockService()->allocate($v['express_first_no'], $warehouseId, false);
        }
    }
}
