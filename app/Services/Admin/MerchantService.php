<?php
/**
 * 商户列表 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\MerchantResource;
use App\Models\Merchant;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Hashing\Argon2IdHasher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MerchantService extends BaseService
{
    public function __construct(Merchant $merchant)
    {
        $this->model = $merchant;
        $this->query = $this->model::query();
        $this->request = request();
        $this->formData = $this->request->all();
        $this->resource = MerchantResource::class;
    }

    /**
     * 商户api 服务
     * @return MerchantApiService
     */
    private function getMerchantApiService()
    {
        return parent::getInstance(MerchantApiService::class);
    }


    /**
     * 新增
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws BusinessLogicException
     * @throws \Exception
     */
    public function store($params)
    {
        $params['password'] = Hash::make(BaseConstService::INITIAL_PASSWORD);
        $merchant = parent::create($params);
        if ($merchant === false) {
            throw new BusinessLogicException('新增失败,请重新操作');
        }
        //生成授权api
        $id = $merchant->getAttribute('id');
        $merchantApi = $this->getMerchantApiService()->create([
            'merchant_id' => $id,
            'key' => bin2hex(random_bytes(5) . time() . $id),
            'secret' => bin2hex(random_bytes(10) . time() . $id)
        ]);
        if ($merchantApi === false) {
            throw new BusinessLogicException('新增失败,请重新操作');
        }
        return $merchant;
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
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败,请重新操作');
        }
    }

    /**
     * 修改密码
     * @param $id
     * @param $data
     * @throws BusinessLogicException
     */
    public function updatePassword($id, $data)
    {
        $rowCount = parent::updateById($id, ['password' => Hash::make($data['password'])]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败,请重新操作');
        }
    }

    /**
     * 状态-启用/禁用
     * @param $id
     * @param $data
     * @throws BusinessLogicException
     */
    public function status($id, $data)
    {
        $rowCount = parent::updateById($id, ['status' => $data['status']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败,请重新操作');
        }
    }

}
