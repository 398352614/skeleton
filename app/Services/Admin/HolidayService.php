<?php
/**
 * 放假 服务
 * User: long
 * Date: 2020/7/22
 * Time: 14:00
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\HolidayResource;
use App\Models\Holiday;
use App\Models\HolidayDate;
use App\Models\Merchant;
use App\Models\MerchantHoliday;
use App\Services\BaseService;
use Illuminate\Support\Carbon;

/**
 * Class HolidayService
 * @package App\Services\Admin
 * @property HolidayDate $holidayDateModel
 * @property MerchantHoliday $merchantHoliday
 */
class HolidayService extends BaseService
{
    private $holidayDateModel;

    private $merchantHoliday;

    public function __construct(Holiday $model, HolidayDate $holidayDate, MerchantHoliday $merchantHoliday)
    {
        parent::__construct($model, HolidayResource::class, HolidayResource::class);
        $this->holidayDateModel = $holidayDate;
        $this->merchantHoliday = $merchantHoliday;
    }

    /**
     * 商户 服务
     * @return MerchantService
     */
    private function getMerchantService()
    {
        return self::getInstance(MerchantService::class);
    }

    public function show($id)
    {
        return parent::getInfo(['id' => $id], ['*']);
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $dateList = $this->check($params);
        //新增放假
        $id = parent::insertGetId(['name' => $params['sname']]);
        if ($id === 0) {
            throw new BusinessLogicException('新增失败');
        }
        //新增放假日期列表
        data_set($dateList, '*.holiday_id', $id);
        $rowCount = $this->holidayDateModel->insertAll($dateList);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败');
        }
    }

    /**
     * 验证
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $dateList = $this->check($data);
        $rowCount = parent::updateById($id, ['name' => $data['name']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败');
        }
        //日期列表-先删除后新增
        $rowCount = $this->holidayDateModel->newQuery()->where('holiday_id', $id)->delete();
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败');
        }
        data_set($dateList, '*.holiday_id', $id);
        $rowCount = $this->holidayDateModel->insertAll($dateList);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        //删除放假
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败');
        }
        //删除日期-放假
        $rowCount = $this->holidayDateModel->newQuery()->where('holiday_id', $id)->delete();
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败');
        }
        //删除商户-放假
        $rowCount = $this->merchantHoliday->newQuery()->where('holiday_id', $id)->delete();
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败');
        }
    }

    /**
     * 验证
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    private function check($params)
    {
        $dateList = array_filter(explode(',', $params['date_list']), function ($value) {
            return Carbon::hasFormat($value, 'Y-m-d');
        });
        if (empty($dateList)) {
            throw new BusinessLogicException('日期格式不正确');
        }
        return $dateList;
    }

    /**
     * 获取商户列表
     * @return array|mixed
     */
    public function merchantIndex()
    {
        if (empty($merchantList)) return [];
        $merchantIdList = $this->merchantHoliday->newQuery()->pluck('merchant_id')->toArray();
        return $this->getMerchantService()->getMerchantPageList(['merchant_id' => ['not in', $merchantIdList]]);
    }

    /**
     * 新增商户列表
     * @param $id
     * @param $merchantIdList
     * @throws BusinessLogicException
     */
    public function storeMerchantList($id, $merchantIdList)
    {
        $info = parent::getInfo(['id' => $id], ['id'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $merchantIdList = explode_id_string($merchantIdList);
        $merchantHoliday = $this->merchantHoliday->newQuery()->whereIn('merchant_id', $merchantIdList)->first();
        if (!empty($merchantHoliday)) {
            throw new BusinessLogicException('商户已分配');
        }
        //新增商户列表
        data_set($merchantIdList, '*.holiday_id', $id);
        $rowCount = $this->merchantHoliday->insertAll($merchantIdList);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败');
        }
    }

    /**
     * 删除商户
     * @param $id
     * @param $merchantId
     * @throws BusinessLogicException
     */
    public function destroyMerchant($id, $merchantId)
    {
        $rowCount = $this->merchantHoliday->newQuery()->where('holiday_id', $id)->where('merchant_id', $merchantId)->delete();
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败');
        }
    }
}