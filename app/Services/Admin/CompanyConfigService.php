<?php
/**
 * 公司配置 服务
 * User: long
 * Date: 2019/12/26
 * Time: 15:56
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Models\CompanyConfig;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Facades\Artisan;

class CompanyConfigService extends BaseService
{
    public function __construct(CompanyConfig $companyConfig)
    {
        parent::__construct($companyConfig);
    }

    /**
     * 订单 服务
     * @return OrderService
     */
    private function getOrderService()
    {
        return self::getInstance(OrderService::class);
    }

    /**
     * 收件人地址 服务
     * @return ReceiverAddressService
     */
    private function getReceiverAddressService()
    {
        return self::getInstance(ReceiverAddressService::class);
    }

    /**
     * 发件人地址 服务
     * @return SenderAddressService
     */
    private function getSenderAddressService()
    {
        return self::getInstance(SenderAddressService::class);
    }

    /**
     * 仓库 服务
     * @return WareHouseService
     */
    private function getWareHouseService()
    {
        return self::getInstance(WareHouseService::class);
    }

    /**
     * 线路 服务
     * @return LineService
     */
    private function getLineService()
    {
        return self::getInstance(LineService::class);
    }

    /**
     * 地址模板 服务
     * @return AddressTemplateService
     */
    private function getAddressTemplateService()
    {
        return self::getInstance(AddressTemplateService::class);
    }

    public function getAddressTemplateList()
    {
        $data = [];
        $data['template_list'] = $this->getAddressTemplateService()->getList([], ['id'], false);
        return $data;
    }

    /**
     * 创建或更新
     * @param $params
     * @throws BusinessLogicException
     */
    public function createOrUpdate($params)
    {
        $order = $this->getOrderService()->getInfo([], ['id'], false);
        if (!empty($order)) {
            throw new BusinessLogicException('已存在订单，不能修改配置信息');
        }
        $receiver = $this->getReceiverAddressService()->getInfo([], ['id'], false);
        if (!empty($receiver)) {
            throw new BusinessLogicException('已存在收件人，不能修改配置信息');
        }
        $sender = $this->getSenderAddressService()->getInfo([], ['id'], false);
        if (!empty($sender)) {
            throw new BusinessLogicException('已存在发件人，不能修改配置信息');
        }
        $warehouse = $this->getWareHouseService()->getInfo([], ['id'], false);
        if (!empty($warehouse)) {
            throw new BusinessLogicException('已存在仓库，不能修改配置信息');
        }
        $line = $this->getLineService()->getInfo([], ['id'], false);
        if (!empty($line)) {
            throw new BusinessLogicException('已存在线路，不能修改配置信息');
        }

        $addressTemplate = $this->getAddressTemplateService()->getInfo(['id' => $params['address_template_id']], ['id'], false);
        if (empty($addressTemplate)) {
            throw new BusinessLogicException('地址模板不存在');
        }
        if (($params['line_rule'] == BaseConstService::LINE_RULE_POST_CODE) && ($params['address_template_id'] != 1)) {
            throw new BusinessLogicException('若线路规则为按邮编分配，则只能选择地址模板一');
        }
        $rowCount = $this->query->updateOrCreate(['company_id' => auth()->user()->company_id], [
            'line_rule' => $params['line_rule'],
            'address_template_id' => $params['address_template_id'],
            'weight_unit' => $params['weight_unit'],
            'currency_unit' => $params['currency_unit'],
            'volume_unit' => $params['volume_unit'],
            'map' => $params['map'],
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        Artisan::call('company:cache --company_id=' . auth()->user()->company_id);
    }
}
