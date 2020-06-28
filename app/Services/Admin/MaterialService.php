<?php


namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Models\Material;
use App\Models\Package;
use App\Services\BaseService;

class MaterialService extends BaseService
{
    public function __construct(Material $material)
    {
        parent::__construct($material);
    }

    /**
     * 验证材料外部标识列表唯一性
     * @param $item
     * @param null $orderNo
     * @throws BusinessLogicException
     */
    public function checkAllUniqueByOutOrderNoList($item, $orderNo = null)
    {
        $where = ['out_order_no' => ['=', $item]];
        if (!empty($orderNo)) {
            $where['order_no'] = ['<>', $orderNo];
        }
        $info = parent::getInfo($where, ['*'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('材料外部标识[:out_order_no]已存在', 1000, ['out_order_no' => $info['out_order_no']]);
        }
    }


}
