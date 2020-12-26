<?php
/**
 * 商户API 服务
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
        'code,name' => ['like', 'keyword'],
    ];

    protected $merchantModel;

    public function __construct(MerchantApi $merchantApi)
    {
        parent::__construct($merchantApi, MerchantApiResource::class, null);
        $this->merchantModel = new Merchant();
    }

    public function getPageList()
    {
        if (!empty($this->filters)) {
            $this->merchantModel->whereIn('');
        }
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
            throw new BusinessLogicException('商户不存在');
        }
        //生成授权api
        $merchantApi = parent::create([
            'merchant_id' => $merchant->id,
            'key' => Hashids::encode(time() . $merchant->id),
            'secret' => Hashids::connection('alternative')->encode(time() . $merchant->id)
        ]);
        if ($merchantApi === false) {
            throw new BusinessLogicException('新增失败,请重新操作');
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
