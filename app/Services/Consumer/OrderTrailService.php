<?php

namespace App\Services\Consumer;

use App\Exceptions\BusinessLogicException;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderTrail;
use App\Models\Package;
use App\Models\PackageTrail;
use App\Models\Scope\CompanyScope;
use App\Services\BaseService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

/**
 * 公司配置服务
 * Class CompanyService
 * @package App\Services\Admin
 */
class OrderTrailService extends BaseService
{
    public $filterRules = [
        'company_id' => ['=', 'company_id'],
        'order_no' => ['=', 'order_no']
    ];

    public function __construct(OrderTrail $model)
    {
        parent::__construct($model);
    }

    /**
     * 查询
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws BusinessLogicException
     */
    public function getPageList()
    {
        if (empty($this->formData['company_id'])) {
            $orderList = Order::query()->where('order_no', $this->formData['order_no'])->get();
        } else {
            $orderList = Order::query()->where('order_no', $this->formData['order_no'])->where('company_id', $this->formData['company_id'])->get();
        }

        if ($orderList->isEmpty()) {
            throw new BusinessLogicException('查无结果，请检查单号和快递公司是否有误');
        }
        foreach ($orderList as $k => $v) {
            $company = collect(Company::query()->where('id', $v['company_id'])->first())->toArray();
            if (empty($company)) {
                throw new BusinessLogicException('公司不存在');
            }
            $orderList[$k]['company_name'] = $company['name'];
            $orderList[$k]['company_web_site'] = $company['web_site'];
            $orderList[$k]['company_logo_url'] = $company['logo_url'];
            $orderList[$k]['order_trail'] = parent::getList(['order_no' => $v['order_no'], 'company_id' => $v['company_id']], ['*'], false, [], ['id' => 'desc']);
            if (app('request')->header('language') == 'en') {
                foreach ($orderList[$k]['order_trail'] as $x => $y) {
                    $content = $y['content'];
                    $content = str_replace('取件', 'Pick-up', $content);
                    $content = str_replace('派件', 'Delivery', $content);
                    $orderList[$k]['order_trail'][$x]['content'] = $content;
                }
            }
        }
        return $orderList;
    }
}
