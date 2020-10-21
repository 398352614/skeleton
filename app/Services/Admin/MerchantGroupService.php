<?php
/**
 * 商户组列表 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\MerchantGroupResource;
use App\Models\MerchantGroup;
use App\Models\TransportPrice;
use App\Services\BaseConstService;
use App\Services\Admin\BaseService;
use Illuminate\Support\Arr;

class MerchantGroupService extends BaseService
{
    public $filterRules = [
        'name' => ['like', 'name']];

    public function __construct(MerchantGroup $merchantGroup)
    {
        parent::__construct($merchantGroup, MerchantGroupResource::class);
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->check($params);
        $merchantGroup = parent::create($params);
        if ($merchantGroup === false) {
            throw new BusinessLogicException('新增失败,请重新操作');
        }
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
        $this->check($data, $id);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $info = $this->getMerchantService()->getInfo(['merchant_group_id' => $id], ['*'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('当前商户组内还有成员,不能删除');
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * 验证
     * @param $params
     * @param $id
     * @throws BusinessLogicException
     */
    private function check(&$params, $id = null)
    {
        $transportPrice = $this->getTransportPriceService()->getInfo(['id' => $params['transport_price_id'], 'status' => BaseConstService::ON], ['*'], false);
        if (empty($transportPrice)) {
            throw new BusinessLogicException('运价不存在或已被禁用');
        }
        //若设置当前为默认的,则原来默认的设置为否
        if (intval($params['is_default']) === 1) {
            $where = empty($id) ? [] : ['id' => ['<>', $id]];
            $where = Arr::add($where, 'is_default', 1);
            $rowCount = parent::update($where, ['is_default' => 2]);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败');
            }
        }
    }

    /**
     * 成员信息
     * @param int $id
     * @return mixed
     */
    public function indexOfMerchant(int $id)
    {
        return $this->getMerchantService()->indexOfMerchant($id);
    }


    /**
     * 批量设置运价
     * @param $data
     * @throws BusinessLogicException
     */
    public function updatePrice($data)
    {
        $ids = json_decode($data['ids'], true);
        for ($i = 0; $i < count($ids); $i++) {
            $info = $this->update(['id' => $ids[$i]], ['transport_price_id' => $data['transport_price_id']]);
            if (empty($info)) {
                throw new BusinessLogicException('批量设置运价失败');
            }
        }
    }
}
