<?php
namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Models\SenderAddress;
use App\Services\BaseService;
use App\Http\Resources\SenderAddressResource;

class SenderAddressService extends BaseService
{
    public function __construct(SenderAddress $senderaddress)
    {
        $this->request = request();
        $this->model = $senderaddress;
        $this->query = $this->model::query();
        $this->resource = SenderAddressResource::class;
        $this->infoResource =SenderAddressResource::class;
        $this->setFilterRules();
    }

    /**
     *列表查询
     * @return mixed
     */
    public function index(){
        $this->query->where('merchant_id',auth()->user()->id);
        return parent::getPaginate();
    }

    /**
     * @param $id
     * @throws BusinessLogicException
     */
    public function show($id){
        $info= parent::getInfo(['id'=>$id,'merchant_id'=>auth()->user()->id],['*'],true);
        if (empty($info)){
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }

    /**
     * 联合唯一检验
     * @param $params
     * @throws BusinessLogicException
     */
    public function check($params,$id = 0){
        $info= parent::getInfo([
            'id'=>['<>', $id],
            'sender'=> $params['sender'],
            'sender_phone'=> $params['sender_phone'],
            'sender_country'=> $params['sender_country'],
            'sender_post_code'=> $params['sender_post_code'],
            'sender_house_number'=> $params['sender_house_number'],
            'sender_city'=> $params['sender_city'],
            'sender_street'=> $params['sender_street'],
        ],['*'],true);
        return $info;
    }
    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params){
        if(!empty($this->Check($params))){
            throw new BusinessLogicException('地址新增失败，已有重复地址');
        }
        $params['merchant_id']=auth()->user()->id;
        $rowCount=parent::create($params);
        if (empty($rowCount)){
            throw new BusinessLogicException('地址新增失败');
        }
    }

    /**
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data){
        $this->Check($data,$id);
        if(!empty($info)){
            throw new BusinessLogicException('发货方地址已存在,不能重复添加');
        }
        $rowCount=parent::update(['id'=>$id,'merchant_id'=>auth()->user()->id],$data);
        if (empty($rowCount)){
            throw new BusinessLogicException('地址修改失败');
        }
    }

    /**
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id){
        $rowCount = parent::delete(['id' => $id,'merchant_id'=>auth()->user()->id]);
        if(empty($rowCount)){
            throw new BusinessLogicException('地址删除失败');
        }
    }
}
