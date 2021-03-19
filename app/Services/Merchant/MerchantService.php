<?php
/**
 * 货主列表 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Models\Merchant;
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
        $rowCount = parent::updateById($id, Arr::only($data, ['name', 'country', 'contacter', 'phone', 'country', 'address']));
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }
}
