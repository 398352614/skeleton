<?php

namespace App\Services\Admin;

use App\Http\Resources\DriverResource;
use App\Http\Resources\TourResource;
use App\Models\Driver;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;

class DriverService extends BaseService
{

    public $filterRules = [
        'status' => ['=', 'status'],
    ];

    public function __construct(Driver $driver)
    {
        $this->model = $driver;
        $this->query = $this->model::query();
        $this->resource = DriverResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    //新增
    public function store($params)
    {

    }

    /**
     * 添加司机
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
            'lisence_material'      => json_encode($this->formData['lisence_material']),
            'government_material'   => json_encode($this->formData['government_material']),
            'avatar'                => $this->formData['avatar'],
            'bank_name'             => $this->formData['bank_name'],
            'iban'                  => $this->formData['iban'],
            'bic'                   => $this->formData['bic'],
            'crop_type'             => $this->formData['crop_type'],
            'is_locked'             => 0,
        ];
        return Driver::create($driver);
    }


}