<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/28
 * Time: 10:25
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\OrderTemplate;


class OrderTemplateService extends BaseService
{
    public function __construct(OrderTemplate $model, $resource = null, $infoResource = null)
    {
        parent::__construct($model, $resource, $infoResource);
    }

    public function show()
    {
        $info = parent::getInfo(['company_id' => auth()->user()->company_id], ['*'], false);
        return $info;
    }

    /**
     * 创建或修改
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function updateById($id, $params)
    {
        $rowCount = $this->updateById($id, $params);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }
}
