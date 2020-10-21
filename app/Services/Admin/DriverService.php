<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\Api\Admin\DriverResource;
use App\Http\Resources\Api\Admin\Api\Admin\TourResource;
use App\Models\Driver;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\CompanyTrait;
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
        parent::__construct($driver, DriverResource::class, DriverResource::class);
    }

    //新增
    public function store($params)
    {
    }

    /**
     * 取件线路服务
     * @return TourService
     */
    public function getTourService()
    {
        return self::getInstance(TourService::class);
    }

    /**
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $data = Arr::except($data, 'password');
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
        $tourList = $this->getTourService()->getList(['driver_id' => $id],['*'],false)->toArray();
        foreach ($tourList as $v){
            if (in_array($v['status'], [BaseConstService::TOUR_STATUS_4, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_2])) {
                throw new BusinessLogicException('仍有未完成的任务，无法删除');
            }
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('司机删除失败');
        }
    }

    /**
     * 添加司机
     * @throws BusinessLogicException
     */
    public function driverRegister()
    {
        $driver = [
            'email' => $this->formData['email'],
            'password' => Hash::make($this->formData['password']),
            'fullname' => $this->formData['fullname'],
            'gender' => $this->formData['gender'],
            'birthday' => $this->formData['birthday'] ?? null,
            'phone' => $this->formData['phone'], // $this->formData['phone_first'] . $this->formData['phone_last'],
            'duty_paragraph' => $this->formData['duty_paragraph'],
            'address' => $this->formData['address'] ?? '',
            'country' => CompanyTrait::getCountry(),
            'lisence_number' => $this->formData['lisence_number'] ?? '',
            'lisence_valid_date' => $this->formData['lisence_valid_date'] ?? null,
            'lisence_type' => $this->formData['lisence_type'] ?? null,
            'lisence_material' => $this->formData['lisence_material'] ?? '',
            'lisence_material_name' => $this->formData['lisence_material_name'] ?? '',
            'government_material' => $this->formData['government_material'] ?? '',
            'government_material_name' => $this->formData['government_material_name'] ?? '',
            'avatar' => $this->formData['avatar'] ?? '',
            'bank_name' => $this->formData['bank_name'] ?? '',
            'iban' => $this->formData['iban'] ?? '',
            'bic' => $this->formData['bic'] ?? '',
            // 'crop_type'             => $this->formData['crop_type'],
        ];

        $rowCount = parent::create($driver);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增司机失败');
        }
    }

    public function resetPassword($id, $params)
    {
        Driver::where('id', $id)->update([
            'password' => Hash::make($params['new_password']),
        ]);
        return;
    }

    public function getPageList()
    {
        if (!empty($this->formData['tour_no'])) {
            $date = Tour::query()->where('tour_no', $this->formData['tour_no'])->first()->toArray()['execution_date'];
            $info = Tour::query()->where('execution_date', $date)->where('status', '<>', BaseConstService::TOUR_STATUS_5)->whereNotNull('driver_id')->pluck('driver_id')->toArray();
            if (!empty($info)) {
                $this->query->whereNotIn('id', $info);
            }
            $this->query->where('is_locked', '=', BaseConstService::DRIVER_TO_NORMAL);
        }
        return parent::getPageList();
    }
}
