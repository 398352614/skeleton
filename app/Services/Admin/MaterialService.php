<?php


namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Models\Material;
use App\Models\Package;
use App\Services\BaseService;

class MaterialService extends BaseService
{
    public function __construct(Material $materia)
    {
        $this->model = $materia;
        $this->query = $this->model::query();
    }

    /**
     * 验证材料外部标识列表唯一性
     * @param $outOrderNoList
     * @param null $orderNo
     * @throws BusinessLogicException
     */
    public function checkAllUniqueByOutOrderNoList($outOrderNoList, $orderNo = null)
    {
        $where = ['out_order_no' => ['in', $outOrderNoList]];
        if (!empty($orderNo)) {
            $where['order_no'] = ['<>', $orderNo];
        }
        $info = parent::getInfo($where, ['*'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('材料外部标识[' . $info['out_order_no'] . ']已存在');
        }
    }


}
