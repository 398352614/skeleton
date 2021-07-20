<?php

namespace App\Services\BaseServices;

use App\Exceptions\BusinessLogicException;
use App\Services\Admin\ApiTimesService;
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
        $this->curl->setAuth([config('tms.routexl_api_key'), config('tms.routexl_api_secret')]);
    }

    /**
     * 获取线路
     * @throws BusinessLogicException
     */
    public function GetRoute(array $data): array
    {
        //传入的数据必须是由 code,latitude,longitude 三个元素组成的数组构成的二维数组
        $locSeq = [];
        Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . '获取优化线路传递的参数为', $data ?? []);
        foreach ($data as $key => $loc) {
            $temp = [];
            $temp['name'] = $loc['batch_no'];
            $temp['lat'] = $loc['place_lat'];
            $temp['lng'] = $loc['place_lon'];
            $locSeq[] = $temp;
        }
        return $this->getTour($locSeq);
    }

    /**
     * @param array $locSeq
     * @return array
     * @throws BusinessLogicException
     */
    public function getTour(array $locSeq): array
    {
        $resp = $this->curl->post(self::BASE_URL . 'tour/', ['locations' => $locSeq], 0);
        if (!$resp || !$resp['feasible']) {
            Log::channel('api')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '线路规划失败或者不可靠');
            Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'res', $resp ?? []);
            return [];
        }

        $res = [];

        foreach ($resp['route'] as $key => $loc) {
            $res[] = $loc['name'];
        }
        Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'resp', $resp);
        $batch = DB::table('batch')->where('batch_no', $resp['route'][1]['name'])->first();
        if (!$batch) {
            throw new BusinessLogicException('优化失败');
        }
        FactoryInstanceTrait::getInstance(ApiTimesService::class)->timesCount('api_directions_times', $batch->company_id);
        return $res; // 获取有序的 code 的序列
    }
}
