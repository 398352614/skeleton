<?php
/**
 * 商户组列表 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\MerchantGroupResource;
use App\Models\MerchantGroup;
use App\Models\TransportPrice;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class MerchantGroupService extends BaseService
{
    public $filterRules = [
        'name' => ['like', 'name']];

    public function __construct(MerchantGroup $merchantGroup)
    {
        $this->model = $merchantGroup;
        $this->query = $this->model::query();
        $this->resource = MerchantGroupResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    /**
     * 商户管理
     * @return MerchantService
     */
    private function getMerchantService()
    {
        return self::getInstance(MerchantService::class);
    }

    /**
     * 运价管理 服务
     * @return TransportPriceService
     */
    private function getTransportPriceService()
    {
        return self::getInstance(TransportPriceService::class);
    }


    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->check($params);
        $params['transport_price_name']=TransportPrice::query()->where('id',$params['transport_price_id'])->value('name');
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
        $data['transport_price_name']=TransportPrice::query()->where('id',$data['transport_price_id'])->value('name');
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败,请重新操作');
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

    public function indexOfMerchant(int $id)
    {
        return $this->getMerchantService()->indexOfMerchant($id);
    }
}
