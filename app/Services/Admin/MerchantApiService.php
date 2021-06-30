<?php
/**
 * 货主API 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\MerchantApiResource;
use App\Models\Merchant;
use App\Models\MerchantApi;
use Vinkla\Hashids\Facades\Hashids;

/**
 * Class MerchantApiService
 * @package App\Services\Admin
 * @property Merchant $merchantModel
 */
class MerchantApiService extends BaseService
{
    public $filterRules = [
    ];

    protected $merchantModel;

    public function __construct(MerchantApi $merchantApi)
    {
        parent::__construct($merchantApi, MerchantApiResource::class, null);
        $this->merchantModel = new Merchant();
    }

    public function getPageList()
    {
        //keyword兼容
        if (!empty($this->formData['keyword'])) {
            $merchantList = $this->getMerchantService()->query
                ->where('name', 'like', $this->formData['keyword'])
                ->orWhere('code', 'like', $this->formData['keyword'])
                ->get();
        }
        //新搜索
        if (!empty($this->formData['code']) || !empty($this->formData['name'])) {
            if (!empty($this->formData['code'])) {
                $where['code'] = $this->formData['code'];
            }
            if (!empty($this->formData['name'])) {
                $where['name'] = $this->formData['name'];
            }
            $merchantList = $this->getMerchantService()->getList($where, ['*'], false);
        }
        if (!empty($merchantList)) {
            $this->query->whereIn('merchant_id', $merchantList->pluck('id')->toArray());
        }
        $list = parent::getPageList();
        foreach ($list as &$merchantApi) {
            $merchant = $this->getMerchantService()->getInfo(['id' => $merchantApi['merchant_id']], ['name', 'code'], false);
            $merchantApi['merchant_id_name'] = $merchant->name ?? '';
            $merchantApi['merchant_id_code'] = $merchant->code ?? '';
        }
        return $list;
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $merchant = $this->getMerchantService()->getInfo(['id' => $params['merchant_id']], ['*'], false);
        if (empty($merchant)) {
            throw new BusinessLogicException('货主不存在');
        }
        $dbMerchantApi = parent::getInfo(['merchant_id' => $params['merchant_id']], ['id'], false);
        if (!empty($dbMerchantApi)) {
            throw new BusinessLogicException('当前货主已创建API对接信息');
        }
        //生成授权api
        $merchantApi = parent::create([
            'merchant_id' => $merchant->id,
            'key' => Hashids::encode(time() . $merchant->id),
            'secret' => Hashids::connection('alternative')->encode(time() . $merchant->id)
        ]);
        if ($merchantApi === false) {
            throw new BusinessLogicException('新增失败，请重新操作');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        return;
    }
}
