<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\DriverResource;
use App\Models\Driver;
use App\Models\Tour;
use App\Services\BaseConstService;
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
        $tourList = $this->getTourService()->getList(['driver_id' => $id], ['*'], false)->toArray();
        foreach ($tourList as $v) {
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
            'lisence_valid_date' => empty($this->formData['lisence_valid_date']) ? null : $this->formData['lisence_valid_date'],
            'lisence_type' => $this->formData['lisence_type'] ?? null,
            'lisence_material' => $this->formData['lisence_material'] ?? '',
            'lisence_material_name' => $this->formData['lisence_material_name'] ?? '',
            'government_material' => $this->formData['government_material'] ?? '',
            'government_material_name' => $this->formData['government_material_name'] ?? '',
            'avatar' => $this->formData['avatar'] ?? '',
            'bank_name' => $this->formData['bank_name'] ?? '',
            'iban' => $this->formData['iban'] ?? '',
            'bic' => $this->formData['bic'] ?? '',
            'type'=>$this->formData['type'] ?? '',
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
        $date = null;
        //如果查询条件中有取件线路，1查询取派日期，2查到这个取派日期的所有取件线路，3把已经在其他取件线路的司机排除。就是这条取件线路可选的司机。
        if (!empty($this->formData['tour_no'])) {
            $tour = Tour::query()->where('tour_no', $this->formData['tour_no'])->first();
            if (!empty($tour)) {
                $date = $tour->toArray()['execution_date'];
            }
            $driverIdList = Tour::query()->where('execution_date', $date)->where('status', '<>', BaseConstService::TOUR_STATUS_5)->whereNotNull('driver_id')->pluck('driver_id')->toArray();
            if (!empty($driverIdList)) {
                $this->query->whereNotIn('id', $driverIdList);
            }
            $this->query->where('is_locked', '=', BaseConstService::DRIVER_TO_NORMAL);
        }
        $this->query->orderByDesc('id');
        return parent::getPageList();
    }
}
