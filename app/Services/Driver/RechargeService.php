<?php

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\RechargeInfoResource;
use App\Http\Resources\Api\Driver\RechargeResource;
use App\Models\MerchantApi;
use App\Models\Recharge;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Class MerchantService
 * @package App\Services\Admin
 */
class RechargeService extends BaseService
{
    public $filterRules = [
        'merchant_id' => ['like', 'merchant_id'],
        'status' => ['=', 'status'],
        'recharge_no' => ['=', 'recharge_no'],
        'recharge_date' => ['between', ['begin_date', 'end_date']],
    ];

    public function __construct(Recharge $recharge)
    {
        parent::__construct($recharge, RechargeResource::class, RechargeInfoResource::class);
    }

    /**
     * 充值记录列表
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPageList()
    {
        if (!empty($this->formData['recharge_statistics_status'])) {
            $rechargeStatisticsList = $this->getRechargeStatisticsService()->getList(['driver_id' => auth()->user()->id], ['*'], false);
            $tourList = $rechargeStatisticsList->where('status', $this->formData['recharge_statistics_status'])->pluck('tour_no')->toArray();
            $this->query->whereIn('tour_no', $tourList);
        }
        $this->query->where('company_id', auth()->user()->company_id)->where('driver_id', auth()->user()->id)->where(['status' => BaseConstService::RECHARGE_STATUS_3])->orderByDesc('updated_at');
        $data = parent::getPageList();
        foreach ($data as $k => $v) {
            $rechargeStatistics = $this->getRechargeStatisticsService()->getInfo(['id' => $v['recharge_statistics_id']], ['*'], false);
            if (!empty($rechargeStatistics)) {
                $data[$k]['recharge_statistics_status'] = $rechargeStatistics->toArray()['status'];
                $data[$k]['recharge_statistics_status_name'] = ConstTranslateTrait::rechargeStatisticsStatusList($data[$k]['recharge_statistics_status']);
            }
        }
        return $data;
    }

    /**
     * 充值记录查询
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id, 'driver_id' => auth()->user()->id, 'company_id' => auth()->user()->company_id]);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }

    /**
     * 获取外部用户
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function getOutUser($params)
    {
        $merchant = $this->getMerchantService()->getInfo(['id' => $params['merchant_id'], 'status' => BaseConstService::RECHARGE_STATUS_1], ['*'], false);
        if (empty($merchant)) {
            throw new BusinessLogicException('该货主未开启充值业务');
        }
        $merchantApi = MerchantApi::query()->where('merchant_id', $params['merchant_id'])->where('recharge_status', BaseConstService::MERCHANT_RECHARGE_STATUS_1)->first();
        if (empty($merchantApi['url']) || empty($merchantApi['secret']) || empty($merchantApi['key'])) {
            throw new BusinessLogicException('该货主未开启充值业务');
        }
        $tour = $this->getTourService()->getInfo(['driver_id' => auth()->user()->id, 'status' => BaseConstService::TOUR_STATUS_4], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('线路未出库，无法进行现金充值');
        }
        $trackingOrderList = $this->getTrackingOrderService()->getList(['tour_no' => $tour['tour_no']], ['*'], false);
        if ($trackingOrderList->isEmpty()) {
            throw new BusinessLogicException('数据不存在');
        }
        $params = Arr::only($params, ['merchant_id', 'out_user_name']);
        $data['data'] = $params;
        $data['type'] = BaseConstService::NOTIFY_RECHARGE_VALIDUSER;
        //请求第三方
        $curl = new CurlClient();
        $res = $curl->merchantPost($merchantApi, $data);
        Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'res', [$res]);
        if (empty($res) || empty($res['ret']) || (intval($res['ret']) != 1) || empty($res['data']) || empty($res['data']['out_user_id']) || empty($res['data']['phone'] || empty($res['data']['email']))) {
            throw new BusinessLogicException('客户不存在，请检查客户编码是否正确');
        } else {
            $params['recharge_no'] = $this->getOrderNoRuleService()->createRechargeNo();
            $params['status'] = BaseConstService::RECHARGE_VERIFY_STATUS_1;
            $params['driver_name'] = auth()->user()->fullname;
            $params['out_user_name'] = $res['data']['email'];
            $params['out_user_id'] = $res['data']['out_user_id'];
            $params['out_user_phone'] = $res['data']['phone'];
            $params['recharge_date'] = Carbon::today()->format('Y-m-d');
            $params['driver_verify_status'] = BaseConstService::RECHARGE_DRIVER_VERIFY_STATUS_1;
            $params['tour_no'] = $tour['tour_no'];
            $params['execution_date'] = $tour['execution_date'];
            $params['line_id'] = $tour['line_id'];
            $params['line_name'] = $tour['line_name'];
            $params = Arr::except($params, 'verify_date');
            $row = parent::create($params);
            if ($row == false) {
                throw new BusinessLogicException('拉取第三方用户信息失败');
            }
        }
        $phoneHead = str_replace(substr($params['out_user_phone'], -4), '', $params['out_user_phone']);
        return [
            'out_user_id' => $params['out_user_id'],
            'email' => $params['out_user_name'],
            'phone_head' => $phoneHead,
            'recharge_no' => $row->getAttributes()['recharge_no']
        ];
    }

    /**
     * 验证手机尾号
     * @param $params
     * @throws BusinessLogicException
     */
    public function verify($params)
    {
        $info = parent::getInfo(['recharge_no' => $params['recharge_no']], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('充值信息丢失，请重新充值');
        }
        if ($info['status'] == BaseConstService::RECHARGE_STATUS_3) {
            throw new BusinessLogicException('充值已完成，请勿重复充值');
        }
        if ($params['verify_phone_end'] !== substr($info['out_user_phone'], -4)) {
            throw new BusinessLogicException('验证失败');
        }
        $row = parent::update(['recharge_no' => $params['recharge_no']], ['status' => BaseConstService::MERCHANT_RECHARGE_STATUS_2]);
        if ($row == false) {
            throw new BusinessLogicException('验证失败');
        }
        return;
    }


    /**
     * 充值
     * @param $params
     * @throws BusinessLogicException
     */
    public function recharge($params)
    {
        //获取并验证数据
        $params = Arr::only($params, ['out_user_id', 'out_user_name', 'recharge_no', 'merchant_id', 'recharge_amount', 'signature', 'recharge_first_pic', 'recharge_second_pic', 'recharge_third_pic', 'remark']);
        $info = parent::getInfoLock(['recharge_no' => $params['recharge_no']], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('充值信息已失效，请重新充值');
        }
        if ($info['status'] == BaseConstService::RECHARGE_STATUS_3) {
            throw new BusinessLogicException('充值已完成，请勿重复充值');
        }
        /*        if ($info['driver_verify_status'] == BaseConstService::RECHARGE_DRIVER_VERIFY_STATUS_2) {
                    throw new BusinessLogicException('手机尾号验证未通过，请重新提交');
                }*/
        if ($params['out_user_id'] !== $info['out_user_id'] || $params['out_user_name'] !== $info['out_user_name']) {
            throw new BusinessLogicException('用户信息不正确，请重新充值');
        }
        //数据组装
        $data['data'] = Arr::only(collect($params)->toArray(), ['out_user_id', 'out_user_name', 'merchant_id', 'out_user_name', 'signature', 'recharge_amount', 'recharge_no']);
        $data['data']['driver_name'] = $info['driver_name'];
        $data['data']['out_user_phone'] = $info['out_user_phone'];
        $data['data']['recharge_screenshot_url'] = $data['data']['signature'];
        $data['data'] = Arr::except($data['data'], 'signature');
        $data['type'] = BaseConstService::NOTIFY_RECHARGE_PROCESS;
        //请求第三方
        $curl = new CurlClient();
        $merchant = MerchantApi::query()->where('merchant_id', $params['merchant_id'])->where('recharge_status', BaseConstService::MERCHANT_RECHARGE_STATUS_1)->first();
        if (empty($merchant)) {
            throw new BusinessLogicException('该货主未开启充值业务');
        }
        $merchantApi = MerchantApi::query()->where('merchant_id', $params['merchant_id'])->where('recharge_status', BaseConstService::MERCHANT_RECHARGE_STATUS_1)->first();
        if (empty($merchantApi['url']) || empty($merchantApi['secret']) || empty($merchantApi['key'])) {
            throw new BusinessLogicException('该货主未开启充值业务');
        }
        $res = $curl->merchantPost($merchant, $data);
        Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'res', $res);
        /*        $res['data']['ret']=1;
                $res['data']['transaction_number']=110;*/
        if (empty($res) || empty($res['ret']) || (intval($res['ret']) != 1) || empty($res['data']) || empty($res['data']['transaction_number'])) {
            $row = parent::update(['id' => $info['id']], ['status' => BaseConstService::RECHARGE_STATUS_2]);

            throw new BusinessLogicException('充值失败');
        } elseif ($res['ret'] == 3) {
            throw new BusinessLogicException('充值请求已过期，请重新充值');
        } else {
            $row = parent::update(['id' => $info['id']], array_merge($params, [
                'status' => BaseConstService::RECHARGE_STATUS_3,
                'transaction_number' => $res['data']['transaction_number'],
                'recharge_time' => now(),
                'recharge_date' => Carbon::today()->format('Y-m-d'),
            ]));
            //充值统计
            $info = $info->toArray();
            $rechargeStatisticsId = $this->getRechargeStatisticsService()->rechargeStatistics($info);
            $row = parent::updateById($info['id'], ['recharge_statistics_id' => $rechargeStatisticsId]);
            if ($row == false) {
                throw new BusinessLogicException('充值统计失败');
            }
            return;
        }
    }
}
