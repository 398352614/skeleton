<?php


namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Models\Material;
use App\Services\BaseService;

class MaterialService extends BaseService
{
    public function __construct(Material $material)
    {
        parent::__construct($material);

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
            throw new BusinessLogicException('材料外部标识[:out_order_no]已存在', 1000, ['out_order_no' => $info['out_order_no']]);
        }
    }

    /**
     * 验证材料编码+外部标识是否重复
     * @param $materialList
     * @throws BusinessLogicException
     */
    public function checkAllUnique($materialList)
    {
        $uniqueCodeList = collect($materialList)->map(function ($material, $key) {
            return $material['code'] . '-' . $material['out_order_no'] ?? '';
        })->toArray();
        if (count(array_unique($uniqueCodeList)) !== count($uniqueCodeList)) {
            $repeatUniqueCodeList = implode(',', array_diff_assoc($uniqueCodeList, array_unique($uniqueCodeList)));
            throw new BusinessLogicException('材料代码-外部标识[:code]有重复！不能添加订单', 1000, ['code' => $repeatUniqueCodeList]);
        }
    }


}
