<?php

namespace App\Services\BaseServices;

use App\Exceptions\BusinessLogicException;
use App\Services\Admin\ApiTimesService;
use App\Services\Admin\BatchService;
use App\Services\CurlClient;
use App\Traits\FactoryInstanceTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Route XL 路线规划服务
 */
class XLDirectionService
{
    const BASE_URL = 'https://api.routexl.com/';

    /**
     * @var array
     */
    private $auth;

    /**
     * @var CurlClient
     */
    private $curl;

    public function __construct()
    {
        $this->curl = new CurlClient();
        $this->curl->setAuth([
            'JackyLu',
            'NLE19901212!'
        ]);
        // $this->auth = [

        // ];
    }

    /**
     * 获取线路
     */
    public function GetRoute(array $data): array
    {
        //传入的数据必须是由 code,latitude,longitude 三个元素组成的数组构成的二维数组
        $locSeq = [];
        app('log')->debug('获取优化线路传递的参数为:', $data ?? []);
        foreach ($data as $key => $loc) {
            $temp = [];
            $temp['name'] = $loc['batch_no'];
            $temp['lat'] = $loc['receiver_lat'];
            $temp['lng'] = $loc['receiver_lon'];
            $locSeq[] = $temp;
        }
        return $this->getTour($locSeq);
    }

    public function getTour(array $locSeq): array
    {
        $resp = $this->curl->post(self::BASE_URL . 'tour/', ['locations' => $locSeq], 0);
        if (!$resp || !$resp['feasible']) {
            app('log')->info('线路规划失败或者不可靠');
            return null;
        }

        $res = [];

        foreach ($resp['route'] as $key => $loc) {
            $res[] = $loc['name'];
        }
        Log::info('请求回复', $resp);
        $batch = DB::table('batch')->where('batch_no', $resp['route'][1]['name'])->first();
        if (!$batch) {
            throw new BusinessLogicException('优化失败');
        }
        FactoryInstanceTrait::getInstance(ApiTimesService::class)->timesCount('api_directions_times', $batch->company_id);
        return $res; // 获取有序的 code 的序列
    }
}
