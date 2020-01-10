<?php
namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\SenderAddress;
use App\Services\BaseService;
use App\Http\Resources\SenderAddressResource;

class SenderAddressService extends BaseService
{
    public function __construct(SenderAddress $senderaddress)
    {
        $this->request = request();
        $this->formData = $this->request->all();
        $this->model = $senderaddress;
        $this->query = $this->model::query();
        $this->resource = SenderAddressResource::class;
        $this->infoResource =SenderAddressResource::class;
        $this->setFilterRules();
    }

    public function index(){
        return parent::getpagelist();
    }

    /**
     * @param $id
     * @throws BusinessLogicException
     */
    public function show($id){
        $info= parent::getInfo(['id'=>$id],['*'],true);
        if (empty($info)){
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params){
        $info= parent::getInfo([
            'sender'=> $params['sender'],
            'sender_phone'=> $params['sender_phone'],
            'sender_country'=> $params['sender_country'],
            'sender_post_code'=> $params['sender_post_code'],
            'sender_house_number'=> $params['sender_house_number'],
            'sender_city'=> $params['sender_city'],
            'sender_street'=> $params['sender_street'],
        ],['*'],true);
        if(!empty($info)){
          throw new BusinessLogicException('地址新增失败，已有重复地址');
        }
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
        $info= parent::getInfo([
            'sender'=> $data['sender'],
            'sender_phone'=> $data['sender_phone'],
            'sender_country'=> $data['sender_country'],
            'sender_post_code'=> $data['sender_post_code'],
            'sender_house_number'=> $data['sender_house_number'],
            'sender_city'=> $data['sender_city'],
            'sender_street'=> $data['sender_street'],
        ],['*'],true);
        if(!empty($info)){
            throw new BusinessLogicException('地址修改失败，已有重复地址');
        }
        $rowCount=parent::updateById($id, $data);
        if (empty($rowCount)){
            throw new BusinessLogicException('地址修改失败');
        }
    }

    /**
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id){
        $rowCount = parent::delete(['id' => $id]);
        if(empty($rowCount)){
            throw new BusinessLogicException('地址删除失败');
        }
    }
}
