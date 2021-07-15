<?php
/**
 * 货主
 * User: long
 * Date: 2020/1/3
 * Time: 16:26
 */

namespace App\Http\Controllers\Api\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Merchant\MerchantService;

/**
 * Class BatchExceptionController
 * @package App\Http\Controllers\Api\Admin
 * @property MerchantService $service
 */
class MerchantController extends BaseController
{
    public function __construct(MerchantService $service)
    {
        parent::__construct($service);
    }

    /**
     * 获取详情
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function show()
    {
        $info = $this->service->getInfo(['id' => auth()->id()], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        return $info;
    }


    /**
     * 修改
     *
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function update()
    {
        $dbInfo = $this->service->getInfo(['name' => $this->data['name'], 'id' => ['<>', auth()->id()]], ['id'], false);
        if (!empty($dbInfo)) {
            throw new BusinessLogicException('商户名称已存在');
        }
        return $this->service->updateById(auth()->id(), $this->data);
    }

}
