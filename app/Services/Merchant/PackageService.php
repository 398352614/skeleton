<?php


namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Services\BaseConstService;
use App\Services\BaseService;
use http\Env\Request;
use Illuminate\Support\Arr;

class PackageService extends BaseService
{
    public function __construct(Package $package)
    {
        parent::__construct($package, PackageResource::class, PackageResource::class);

    }

    public $filterRules = [
        'express_first_no' => ['=', 'express_first_no'],
        'express_second_no' => ['=', 'express_second_no'],
        'out_order_no' => ['=', 'out_order_no'],
    ];

    /**
     * 验证
     * @param $packageList
     * @param null $orderNo
     * @throws BusinessLogicException
     */
    public function check($packageList, $orderNo = null)
    {
        $expressFirstNoList = array_column($packageList, 'express_first_no');
        if (count($expressFirstNoList) !== count(array_unique($expressFirstNoList))) {
            $repeatExpressFirstNoList = implode(',', array_diff_assoc($expressFirstNoList, array_unique($expressFirstNoList)));
            throw new BusinessLogicException('快递单号[:express_no]有重复！不能添加订单', 1000, ['express_no' => $repeatExpressFirstNoList]);
        }
        //存在快递单号2,则验证
        $expressSecondNoList = array_filter(array_column($packageList, 'express_second_no'));
        if (!empty($expressSecondNoList)) {
            if (count($expressSecondNoList) !== count(array_unique($expressSecondNoList))) {
                $repeatExpressSecondNoList = implode(',', array_diff_assoc($expressSecondNoList, array_unique($expressSecondNoList)));
                throw new BusinessLogicException('快递单号2[:express_no]有重复！不能添加订单', 1000, ['express_no' => $repeatExpressSecondNoList]);
            }
            $expressSecondNoCountList = array_count_values($expressSecondNoList);
            $firstPackage = Arr::first($packageList, function ($package) use ($expressSecondNoCountList) {
                if (empty($expressSecondNoCountList[$package['express_first_no']])) return false;
                if (($expressSecondNoCountList[$package['express_first_no']] == 1) && (!empty($package['express_second_no']) && ($package['express_first_no'] == $package['express_second_no']))) return false;
                return true;
            });
            if (!empty($firstPackage)) {
                throw new BusinessLogicException('快递单号1[:express_no]已存在在快递单号2中', 1000, ['express_no' => $firstPackage['express_first_no']]);
            }
            $expressFirstNoCountList = array_count_values($expressFirstNoList);
            $secondPackage = Arr::first($packageList, function ($package) use ($expressFirstNoCountList) {
                if (empty($package['express_second_no']) || empty($expressFirstNoCountList[$package['express_second_no']])) return false;
                if (($expressFirstNoCountList[$package['express_second_no']] == 1) && ($package['express_first_no'] == $package['express_second_no'])) return false;
                return true;
            });
            if (!empty($secondPackage)) {
                throw new BusinessLogicException('快递单号2[:express_no]已存在在快递单号1中', 1000, ['express_no' => $firstPackage['express_second_no']]);
            }
        }
        //验证外部标识/快递单号1/快递单号2
        $this->checkAllUnique($packageList, $orderNo);
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
                $errorMsg = '';
                if (!empty($package['express_first_no'])) {
                    $errorMsg .= __('包裹快递单号1[:express_no]已存在;', ['express_no' => $package['express_first_no']]);
                }
                if (!empty($package['express_second_no'])) {
                    $errorMsg .= __('包裹快递单号2[:express_no]已存在;', ['express_no' => $package['express_second_no']]);
                }
                if (!empty($package['out_order_no'])) {
                    $errorMsg .= __('包裹外部标识[:out_order_no]已存在;', ['out_order_no' => $package['out_order_no']]);
                }
                //第三方特殊处理
                if (auth()->user()->getAttribute('is_api') == true) {
                    $order = $this->getOrderService()->getInfo(['order_no' => $dbPackage['order_no']])['order_no'];
                    $errorMsg = '订单[' . $order . ']:' . $errorMsg;
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
        $query = $this->model::query();
        //若存在订单号,则排除
        if (!empty($orderNo)) {
            $query->where('order_no', '<>', $orderNo);
        }
        $orWhere = [
            'express_first_no' => $package['express_first_no'],
            'express_second_no' => $package['express_first_no']
        ];
        //若存在快递单号2,则验证
        if (!empty($package['express_second_no'])) {
            $orWhere['express_second_no'] = $package['express_second_no'];
            $orWhere['express_second_no'] = $package['express_first_no'];
        }
        //若存在外部标识,则验证
//        if (!empty($package['out_order_no'])) {
//            $orWhere['out_order_no'] = $package['out_order_no'];
//        }
        $query->where(function ($query) use ($orWhere) {
            foreach ($orWhere as $key => $value) {
                $query->where($key, '=', $value, 'or');
            }
        });
        $result = $query->whereNotIn('status', [BaseConstService::PACKAGE_STATUS_6, BaseConstService::PACKAGE_STATUS_7])->first();
        return !empty($result) ? $result->toArray() : [];
    }

    /**
     * 查询包裹信息
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function showByApi($params)
    {
        if (!empty($params['express_first_no'])) {
            $this->query->where('express_first_no', '=', $params['express_first_no']);
        }
        if (!empty($params['express_second_no'])) {
            $this->query->where('express_second_no', '=', $params['express_second_no']);
        }
        if (!empty($params['out_order_no'])) {
            $this->query->where('out_order_no', '=', $params['out_order_no']);
        }
        $this->query->whereNotIn('status', [BaseConstService::PACKAGE_STATUS_6, BaseConstService::PACKAGE_STATUS_7]);
        $info = $this->getPageList()->toArray(request());
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $order = $this->getOrderService()->getInfo(['order_no' => $info[0]['order_no']]);
        if (empty($order)) {
            throw new BusinessLogicException('数据不存在');
        }
        return [
            'order_no' => $info[0]['order_no'],
            'out_order_no' => $order['out_order_no']
        ];
    }

    /**
     * 订单服务
     * @return OrderService
     */
    public function getOrderService()
    {
        return self::getInstance(OrderService::class);
    }
}
