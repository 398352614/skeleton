<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Services\Admin\DriverService;
use Illuminate\Http\Request;

class DriverController extends BaseController
{
    public $service;

    public function __construct(DriverService $service)
    {
        $this->service = $service;
    }

    /**
     * 司机注册.
     *
     * @return void
     */
    public function driverRegister(Request $request)
    {
        if (!$request->has('password') || !$request->has('confirm_password')) {
            return formatRet(400, __('messages.password.confirm'), []);
        }

        if (in_array($request->mobile_first, ['0031', '0032'])) {
            if (strlen($request->mobile_last) != 9) {
                return formatRet(400, __('messages.mobile.length_nine'), []);
            }
        }
        if (in_array($request->mobile_first, ['0049'])) {
            if (strlen($request->mobile_last) != 10) {
                return formatRet(400, __('messages.mobile.length_ten'), []);
            }
        }

        if (count($request->lisence_material) > 10) {
            return formatRet(400, __('messages.material.license'), []);
        }
        if (count($request->government_material) > 10) {
            return formatRet(400, __('messages.material.government'), []);
        }

        // $relation_code = $request->input('relation_code');
        // $relation = DriverSiteInvitationModel::getIns()->getDriverSiteInvitation($relation_code);
        // if (!$relation) {
        //     return formatRet(400, __('messages.regist.invalid'), []);
        // }
        // $relation = $relation->toArray();
        // if ($relation['email'] != $request->input('email')) {
        //     return formatRet(400, __('messages.regist.invalid'), []);
        // }

        $password = $request->input('password');
        $confirm_password = $request->input('confirm_password');
        if ($password != $confirm_password) {
            return formatRet(400, __('messages.password.inconsistent'), []);
        }

        $user = [
            'name' => $request->last_name . $request->first_name,
            'email' => $request->email,
            'user_type' => UsersModel::DRIVER,
            'password' => Hash::make($request->password)
        ];
        $user_id = UsersModel::create($user);
        if ($user_id->id < 0) {
            return formatRet(400, __('messages.regist.failed'), []);
        }

        $driver = [
            'user_id' => $user_id->id,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'first_name' => $request->first_name,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'mobile' => $request->mobile_first . $request->mobile_last,
            'duty_paragraph' => $request->duty_paragraph,
            'post_code' => $request->post_code,
            'door_no' => $request->door_no,
            'street' => $request->street,
            'city' => $request->city,
            'country' => $request->country,
            'lisence_number' => $request->lisence_number,
            'lisence_valid_date' => $request->lisence_valid_date,
            'lisence_type' => $request->lisence_type,
            'lisence_material' => json_encode($request->lisence_material),
            'government_material' => json_encode($request->government_material),
            'avatar' => $request->avatar,
            'bank_name' => $request->bank_name,
            'iban' => $request->iban,
            'bic' => $request->bic,
            'status' => DriverModel::AUDIT_SUCCEED
        ];
        $driver_id = DriverModel::create($driver);
        $site_id = auth()->payload()->get('site_id');
        if ($driver_id) {
            $data = [
                'driver_id' => $user_id->id,
                'site_id' => $site_id
            ];
            DriverSiteModel::create($data);

            return formatRet(200, __('messages.regist.review'), []);
        } else {
            return formatRet(400, __('messages.regist.failed'), []);
        }
    }

    /**
     * 获取司机详情.
     *
     * @return void
     */
    public function show($id)
    {
        if (!is_numeric($id) || $id < 0) {
            return formatRet(400, __('messages.params.type'), []);
        }

        if (!$driver = DriverModel::find($id)) {
            return formatRet(400, __('messages.driver.does_not_exist'), []);
        }
        $data = $driver->toArray();

        $data['lisence_material'] = json_decode($data['lisence_material']);
        $data['government_material'] = json_decode($data['government_material']);

        return formatRet(200, __('messages.driver.success'), $data);
    }

    /**
     * 获取当前网点司机列表.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'integer|min:1',
            'page_size' => new PageSize,
            'status' => 'nullable|integer|in:1,2,3',
            'crop_type' => 'nullable|integer|in:10,20',
            'keywords' => 'nullable|string',
        ]);
        $site_id = auth()->payload()->get('site_id');

        $data = [
            'page' => ($request->page) ? ($request->page) : 1,
            'page_size' => $request->page_size,
            'status' => $request->status,
            'crop_type' => $request->crop_type,
            'keywords' => $request->keywords,
            'site_id' => $site_id
        ];
        $datas = DriverSiteModel::getIns()->list($data);
        $datas = $datas->toArray();

        // 如果有数据
        if (isset($datas['data'][0]['user_id'])) {
            foreach ($datas['data'] as $key => $value) {
                $datas['data'][$key]['driver_id'] = $value['user_id'];
                $datas['data'][$key]['workday'] = $value['workday'] ? json_decode($value['workday']) : null;
                $datas['data'][$key]['workday_zh'] = $value['workday'] ? workday($value['workday']) : null;
                $datas['data'][$key]['status_name'] = DriverSiteModel::getIns()->getStatusName($value['status']);
                $datas['data'][$key]['crop_type_name'] = DriverSiteModel::getIns()->getCropTypeName($value['crop_type']);
                $datas['data'][$key]['lisence_material'] = json_decode($value['lisence_material']);
                $datas['data'][$key]['government_material'] = json_decode($value['government_material']);
                $datas['data'][$key]['business_range'] = $value['business_range'] ? json_decode($value['business_range']) : null;

                unset($datas['data'][$key]['user_id']);
            }
        }
        return formatRet(200, __('messages.driver.success'), $datas);
    }

    /**
     * 获取司机工作日.
     *
     * @return void
     */
    public function driverWork(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|integer',
        ]);
        $driver_id = $request->driver_id;
        $site_id = auth()->payload()->get('site_id');

        if (!$driver = DriverModel::getIns()->getDriver($driver_id)) {
            return formatRet(400, __('messages.driver.does_not_exist'), []);
        }
        if (!$driverWork = DriverSiteModel::getIns()->getWork($driver_id)) {
            return formatRet(400, __('messages.driver.driver_not_site'), []);
        }

        $driverWorkday = new DriverWorkdayService();
        $workday = $driverWorkday->driverWorkday($driver_id, $site_id);

        return formatRet(200, __('messages.driver.work_info'), $workday);
    }

    /**
     * 获取合作方式.
     *
     * @return void
     */
    public function cropType()
    {
        $cropType = [
            [
                'crop_type' => DriverSiteModel::HIRE,
                'type_name' => '雇佣'
            ],
            [
                'crop_type' => DriverSiteModel::CONTRACTOR,
                'type_name' => '包线'
            ]
        ];
        return formatRet(200, __('messages.driver.crop_type'), $cropType);
    }

    /**
     * 获取状态.
     *
     * @return void
     */
    public function driverStatus()
    {
        $status = [
            [
                'status' => DriverSiteModel::TO_AUDIT,
                'status_name' => '待审核'
            ],
            [
                'status' => DriverSiteModel::TO_LOCK,
                'status_name' => '锁定'
            ],
            [
                'status' => DriverSiteModel::TO_NORMAL,
                'status_name' => '正常'
            ]
        ];
        return formatRet(200, __('messages.status.success'), $status);
    }

    /**
     * 给司机分配工作信息（也就是产品图上的审核）.
     *
     * @return void
     */
    public function assginDriverWork(Request $request)
    {
        $request->validate([
            'crop_type' => 'required|in:10,20',
            'workday' => 'required|array',
            'business_range' => 'required|array',
            'id' => 'required|integer',
            'driver_id' => 'required|integer',
        ]);
        $site_id = auth()->payload()->get('site_id');

        $business_range = $request->business_range;

        foreach ($business_range as $key => $value) {

            if (strlen($value[0]) != 4 || strlen($value[1]) != 4 || !is_numeric($value[0]) || !is_numeric($value[1])) {
                return formatRet(400, __('messages.driver.business_range'), []);
            }
            if ($value[0] > $value[1]) {
                return formatRet(400, __('messages.driver.business_range_number'), []);
            }
        }
        // 本网点的工作日
        $workday = $request->workday;
        // 司机在其他网点的工作日
        $other = DriverSiteModel::getIns()->driverOtherWorkday($request->driver_id, $site_id);
        $other = $other->toArray();
        $day = array();
        if (isset($other[0]['workday'])) {
            foreach ($other as $key => $value) {
                $work = json_decode($value['workday']);
                foreach ($work as $k => $v) {
                    $day[] = $k;
                }
            }
            foreach ($workday as $key => $value) {
                if (in_array($key, $day)) {
                    return formatRet(400, $key . __('messages.driver.other_site'), []);
                }
            }
        }
        $data = [
            'crop_type' => $request->crop_type,
            'workday' => json_encode($request->workday),
            'business_range' => json_encode($request->business_range),
            'status' => DriverSiteModel::TO_NORMAL,
            'driver_id' => $request->driver_id,
            'site_id' => $site_id
        ];

        $siteId = DriverSiteModel::getIns()->driverSite($request->driver_id, $site_id);
        if (!$siteId) {
            $update = DriverSiteModel::create($data);
        } else {
            $update = DriverSiteModel::getIns()->updateDriverWorkday($data);
        }

        if ($update) {
            // 发送邮件通知司机工作的信息
            $driver = DriverModel::getIns()->getDriver($request->driver_id);
            $site = SiteModel::getIns()->getSiteById($site_id);
            $params = [
                'email' => $driver->email,
                'workday' => workdayString($workday),
                'site_name' => $site->name_cn,
                'phone' => $site->phone,
                'label' => 'emails.driverWorkday',
                'type' => '司机工作信息邮件'
            ];
            $emailService = new EmailService();
            $emailService->sendEmail($params);

            return formatRet(200, __('messages.add.success'), []);
        } else {
            return formatRet(400, __('messages.add.failed'), []);
        }
    }

    /**
     * 修改司机状态（是否锁定）.
     *
     * @return void
     */
    public function lockDriver(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|in:2,3',
        ]);
        $site_id = auth()->payload()->get('site_id');
        // 判断数据存不存在
        if (!$info = DriverSiteModel::find($request->id)) {
            return formatRet(400, __('messages.driver.not_data'), []);
        }
        // 判断有没有修改的权限
        $site = SiteModel::getIns()->getSites(Auth::id(), $site_id);
        if (!$site) {
            return formatRet(400, __('messages.update.invalid'), []);
        }
        $data = [
            'id' => $request->id,
            'status' => $request->status
        ];
        $update = DriverSiteModel::getIns()->updateLock($data);
        if ($update) {
            return formatRet(200, __('messages.update.success'), []);
        } else {
            return formatRet(400, __('messages.update.failed'), []);
        }
    }

    /**
     * 删除司机.
     *
     * @return void
     */
    public function destroy(Request $request, $id)
    {
        $request->validate([
            'ids' => 'required|array'
        ]);
        $site_id = auth()->payload()->get('site_id');
        // 判断有没有删除的权限
        $site = SiteModel::getIns()->getSites(Auth::id(), $site_id);
        if (!$site) {
            return formatRet(400, __('messages.delete.invalid'), []);
        }

        $driver_site = DriverSiteModel::find($request->ids);
        $driver_site = $driver_site->toArray();
        if (!$driver_site && !isset($driver_site[0]['id'])) {
            return formatRet(400, __('messages.delete.failed'), []);
        }
        $site_ids = array();
        foreach ($driver_site as $key => $value) {
            if ($value['site_id'] != $site_id) {
                return formatRet(400, __('messages.delete.failed'), []);
            }
        }
        $delete = DriverSiteModel::destroy($request->ids);
        if ($delete) {
            return formatRet(200, __('messages.delete.success'), []);
        } else {
            return formatRet(400, __('messages.delete.failed'), []);
        }
    }
}
