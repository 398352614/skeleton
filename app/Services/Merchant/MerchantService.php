<?php
/**
 * 商户列表 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Models\Merchant;
use App\Services\BaseService;
use Illuminate\Support\Arr;


class MerchantService extends BaseService
{
    public function __construct(Merchant $merchant)
    {
        parent::__construct($merchant);

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
        if (!empty($params['advance_days']) && !empty($params['appointment_days']) && (intval($params['advance_days']) >= intval($params['appointment_days']))) {
            throw new BusinessLogicException('可预约天数必须大于提前下单天数');
        }
        $rowCount = parent::updateById($id, Arr::only($data, ['name', 'country', 'contacter', 'phone', 'country', 'address']));
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }
}
