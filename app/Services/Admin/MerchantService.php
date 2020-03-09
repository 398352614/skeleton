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
use App\Models\MerchantGroup;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\ConstTranslateTrait;
use App\Traits\ExportTrait;
use Illuminate\Hashing\Argon2IdHasher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class MerchantService extends BaseService
{
    use ExportTrait;
    public $filterRules = [
        'name' => ['like', 'name'],
        'merchant_group_id'=>['=','merchant_group_id']];

    protected $headings= [
    'type',
    'name',
    'email' ,
    'settlement_type' ,
    'merchant_group_id',
    'contacter',
    'phone' ,
    'address',
    'status'
    ];

    public function __construct(Merchant $merchant)
    {
        $this->model = $merchant;
        $this->query = $this->model::query();
        $this->request = request();
        $this->formData = $this->request->all();
        $this->resource = MerchantResource::class;
        $this->setFilterRules();
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
     * 商户组管理 服务
     * @return MerchantGroupService
     */
    private function getMerchantGroupService()
    {
        return self::getInstance(MerchantGroupService::class);
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
        $this->check($params);
        $params['password'] = Hash::make(BaseConstService::INITIAL_PASSWORD);
        $merchant = parent::create($params);
        if ($merchant === false) {
            throw new BusinessLogicException('新增失败,请重新操作');
        }
        //生成授权api
        $id = $merchant->getAttribute('id');
        $merchantApi = $this->getMerchantApiService()->create([
            'merchant_id' => $id,
            'key' => Hashids::encode(time() . $id),
            'secret' => Hashids::connection('alternative')->encode(time() . $id)
        ]);
        if ($merchantApi === false) {
            throw new BusinessLogicException('新增失败,请重新操作');
        }
        MerchantGroup::query()->where('id',$params['merchant_group_id'])->increment('count');
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
        $this->check($data);
        $info=$this->getInfo(['id'=>$id],['merchant_group_id'],false);
        MerchantGroup::query()->where('id',$info['merchant_group_id'])->decrement('count');
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败,请重新操作');
        }
        MerchantGroup::query()->where('id',$data['merchant_group_id'])->increment('count');
    }

    /**
     * 验证
     * @param $params
     * @throws BusinessLogicException
     */
    public function check(&$params)
    {
        $merchantGroup = $this->getMerchantGroupService()->getInfo(['id' => $params['merchant_group_id']], ['*'], false);
        if (empty($merchantGroup)) {
            throw new BusinessLogicException('商户组不存在');
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

    /**
     * 批量启用禁用
     * @param $data
     * @throws BusinessLogicException
     */
    public function statusByList($data)
    {
        $ids=json_decode ($data['ids'], true);
        for($i=0;$i<count($ids);$i++){
            $rowCount[$i] = parent::updateById($ids[$i], ['status' => $data['status']]);
            if ($rowCount === false) {
                throw new BusinessLogicException('修改失败,请重新操作');
            }
        }
    }

    /**
     * 组内商家
     * @param $group_id
     * @return mixed
     */
    public function indexOfMerchant($group_id)
    {
        $this->query->where('merchant_group_id', $group_id);
        return parent::getPaginate();
    }

    /**
     * @return array
     * @throws BusinessLogicException
     */
    public function merchantExcel()
    {
        $cellData=[];
        $info =$this->getList([],$this->headings,false)->toArray();
        for($i=0;$i<count($info);$i++) {
            $info[$i]['merchant_group_id']=$this->getMerchantGroupService()->getInfo(['id'=>$info[$i]['merchant_group_id']],['name'],false)->toArray()['name'];
            $info[$i]['type']=empty($info[$i]['type']) ? null : ConstTranslateTrait::$merchantTypeList[$info[$i]['type']];
            $info[$i]['settlement_type']=empty($info[$i]['settlement_type']) ? null : ConstTranslateTrait::$merchantSettlementTypeLsit[$info[$i]['settlement_type']];
            $info[$i]['status']=empty($info[$i]['status']) ? null : ConstTranslateTrait::$merchantStatusList[$info[$i]['status']];
            for($j=0;$j<count($info[$i]);$j++){
               $cellData[$i][$j]=array_values($info[$i])[$j];
           }
        }
        return $this->excelExport('merchant',$this->headings,$cellData,'merchant');
    }

}
