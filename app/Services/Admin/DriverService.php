<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\DriverResource;
use App\Http\Resources\TourResource;
use App\Models\Driver;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class DriverService extends BaseService
{

    public $filterRules = [
        'status' => ['=', 'status'],
        'email' => ['=', 'email'],
        'phone' => ['=', 'phone']
    ];

    public function __construct(Driver $driver)
    {
        $this->model = $driver;
        $this->query = $this->model::query();
        $this->resource = DriverResource::class;
        $this->infoResource = DriverResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    //新增
    public function store($params)
    {
    }

    /**
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $data=Arr::except($data,'password');
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败,请重新操作');
        }
    }

    /**
     * 添加司机
     * @throws BusinessLogicException
     */
    public function driverRegister()
    {
        $driver = [
            'email'                 => $this->formData['email'],
            'password'              => Hash::make($this->formData['password']),
            'last_name'             => $this->formData['last_name'],
            'first_name'            => $this->formData['first_name'],
            'gender'                => $this->formData['gender'],
            'birthday'              => $this->formData['birthday'],
            'phone'                 => $this->formData['phone'], // $this->formData['phone_first'] . $this->formData['phone_last'],
            'duty_paragraph'        => $this->formData['duty_paragraph'],
            'post_code'             => $this->formData['post_code'],
            'door_no'               => $this->formData['door_no'],
            'street'                => $this->formData['street'],
            'city'                  => $this->formData['city'],
            'country'               => $this->formData['country'],
            'lisence_number'        => $this->formData['lisence_number'],
            'lisence_valid_date'    => $this->formData['lisence_valid_date'],
            'lisence_type'          => $this->formData['lisence_type'],
            'lisence_material'      => json_encode($this->formData['lisence_material'])??'',
            'lisence_material_name' => $this->formData['lisence_material_name']??'',
            'government_material'   => json_encode($this->formData['government_material'])??'',
            'government_material_name'=> $this->formData['government_material_name']??'',
            'avatar'                => $this->formData['avatar'],
            'bank_name'             => $this->formData['bank_name'],
            'iban'                  => $this->formData['iban'],
            'bic'                   => $this->formData['bic'],
            // 'crop_type'             => $this->formData['crop_type'],
        ];

        $rowCount =  parent::create($driver);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增司机失败');
        }
    }

    public function resetPassword($id, $password)
    {
        Driver::where('id', $id)->update([
            'password'  =>  Hash::make($password),
        ]);
        return;
    }

    public function getPageList()
    {
        if(!empty($this->formData['tour_no'])){
            $date =Tour::query()->where('tour_no',$this->formData['tour_no'])->first()->toArray()['execution_date'];
            $info=Tour::query()->where('execution_date',$date)->where('status','<>',BaseConstService::TOUR_STATUS_5)->whereNotNull('driver_id')->pluck('driver_id')->toArray();
            if(!empty($info)){
                $this->query->whereNotIn('id',$info);
            }
            $this->query->where('is_locked','=',BaseConstService::DRIVER_TO_NORMAL);
        }
        return parent::getPageList();
    }
}
