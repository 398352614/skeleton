<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Merchant\SenderAddressService;

/**
 * Class SenderAddressController
 * @package App\Http\Controllers\Api\Merchant
 * @property SenderAddressService $service
 */
class SenderAddressController extends BaseController
{
    public function __construct(SenderAddressService $service)
    {
        parent::__construct($service);
    }

    /**
     * @return mixed
     * @throws BusinessLogicException
     */
    public function index()
    {
        return $this->service->getPageList();
    }


    /**
     * 获取详情
     *
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * @return mixed
     * @throws BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * @param $id
     * @return bool|int
     * @throws BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }

    /**
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
