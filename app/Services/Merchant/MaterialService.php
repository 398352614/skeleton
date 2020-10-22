<?php


namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Models\Material;
use App\Services\Admin\Merchant\OrderService;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class MaterialService extends BaseService
{
    public function __construct(Material $material)
    {
        parent::__construct($material);

    }

    public $filterRules = [
        'tour_no' => ['like', 'tour_no'],
        'batch_no' => ['like', 'batch_no'],
        'order_no' => ['like', 'order_no'],
        'express_first_no,order_no,out_order_no' => ['like', 'keyword'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
    ];

    /**
     * 列表查询
     * @return Collection
     */
    public function getPageList()
    {
        if (!empty($this->formData['merchant_id']) && empty($this->formData['order_no'])) {
            $orderList = $this->getOrderService()->getList(['merchant_id' => $this->formData['merchant_id']], ['*'], false);
            $this->query->whereIn('order_no', $orderList);
        } elseif (!empty($this->formData['merchant_id']) && !empty($this->formData['order_no'])) {
            $orderList = $this->getOrderService()->getList(['merchant_id' => $this->formData['merchant_id']], ['*'], false);
            $this->query->whereIn('order_no', $orderList)->where('order_no', 'like', $this->formData['order_no']);
        }
        $this->query->orderByDesc('updated_at');
        return parent::getPageList();
    }

    /**
     * 查看详情
     * @param $id
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
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
            return $material['code'] . '-' . ($material['out_order_no'] ?? '');
        })->toArray();
        if (count(array_unique($uniqueCodeList)) !== count($uniqueCodeList)) {
            $repeatUniqueCodeList = implode(',', array_diff_assoc($uniqueCodeList, array_unique($uniqueCodeList)));
            throw new BusinessLogicException('材料代码-外部标识[:code]有重复！不能添加订单', 1000, ['code' => $repeatUniqueCodeList]);
        }
    }


}
