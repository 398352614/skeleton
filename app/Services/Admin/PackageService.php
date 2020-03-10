<?php


namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Models\Package;
use App\Services\BaseConstService;
use App\Services\BaseService;

class PackageService extends BaseService
{
    public function __construct(Package $package)
    {
        $this->model = $package;
        $this->query = $this->model::query();
    }

    /**
     * 列表包裹验证唯一
     * @param $packageList
     * @param null $orderNo
     * @throws BusinessLogicException
     */
    public function checkAllUnique($packageList, $orderNo = null)
    {
        foreach ($packageList as $package) {
            $dbPackage = $this->checkUnique($package, $orderNo);
            if (!empty($dbPackage)) {
                $intersectPackage = array_intersect_assoc($dbPackage, $package);
                $errorMsg = '包裹';
                if (!empty($intersectPackage['express_first_no'])) {
                    $errorMsg .= __('快递单号1[:express_no]已存在;', ['express_no' => $intersectPackage['express_first_no']]);
                }
                if (!empty($intersectPackage['express_second_no'])) {
                    $errorMsg .= __('快递单号2[:express_no]已存在;', ['express_no' => $intersectPackage['express_second_no']]);
                }
                if (!empty($intersectPackage['out_order_no'])) {
                    $errorMsg .= __('外部标识[:out_order_no]已存在;', ['express_no' => $intersectPackage['out_order_no']]);
                }
                throw new BusinessLogicException($errorMsg);
            }
        }


    }

    /**
     * 单个包裹验证唯一
     * @param $package
     * @param null $orderNo
     * @return array
     */
    public function checkUnique($package, $orderNo = null)
    {
        $query = $this->model::query()
            ->where('express_first_no', '=', $package['express_first_no'])
            ->orWhere('express_second_no', '=', $package['express_first_no']);
        //若存在快递单号2,则验证
        if (!empty($package['express_second_no'])) {
            $query
                ->orWhere('express_second_no', '=', $package['express_second_no'])
                ->orWhere('express_second_no', '=', $package['express_first_no']);
        }
        //若存在外部标识,则验证
        if (!empty($package['out_order_no'])) {
            $query->orWhere('out_order_no', '=', $package['out_order_no']);
        }
        //若存在订单号,则排除
        if (!empty($orderNo)) {
            $query->where('order_no', '<>', $orderNo);
        }
        $result = $query->whereNotIn('status', [BaseConstService::PACKAGE_STATUS_7])->first();
        return !empty($result) ? $result->toArray() : [];
    }
}
