<?php
/**
 * 测试接口
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 14:31
 */

namespace App\Http\Controllers\Api;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\TestService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class TestController
 * @package App\Http\Controllers\Api
 * @property TestService $service;
 */
class TestController extends BaseController
{
    public function __construct(TestService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        $routeAs = request()->route()->getName();
        return $routeAs;
        /*        $i = 0;
                $info = DB::table('order')->get()->chunk(50)->toArray();
                foreach ($info as $k) {
                    $k = collect($k)->where('batch_no', '<>', '')->all();
                    foreach ($k as $v) {
                        $batch = DB::table('batch')->where('batch_no', $v->batch_no)->first();
                        if (empty($batch)) {
                            DB::table('order')->where('batch_no', $v->batch_no)->delete();
                            DB::table('package')->where('batch_no', $v->batch_no)->delete();
                            DB::table('material')->where('batch_no', $v->batch_no)->delete();
                        }
                    }
                    unset($k);
                }

                $info = DB::table('tour')->get()->toArray();
                foreach ($info as $k) {
                    $batch = DB::table('batch')->where('tour_no', $k->tour_no)->first();
                    if (empty($batch)) {
                        DB::table('tour')->where('tour_no', $k->tour_no)->delete();
                        $i=$i+1;
                    }
                }
                return $i;*/
        return $this->service->getPageList();
    }

    public function show($id)
    {
        $url = 'http://api.map.baidu.com/batch';
        $list = [
            [
                "method" => "get",
                "url" => "/geocoding/v3/?address=重庆市沙坪坝区学城大道62号&ak=你的ak&output=json"
            ],
            [
                "method" => "get",
                "url" => "/geocoding/v3/?address=重庆市沙坪坝区学城大道62号&ak=你的ak&output=json"
            ]
        ];
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', $url, [
            'form_params' => ['reqs' => $list]
        ]);
        $body = $res->getBody();
        $stringBody = (string)$body;
        $arrayBody = json_decode($stringBody, TRUE);

        print_r($arrayBody);
        exit;

        return $this->service->show($id);
    }

    /**
     * 新增
     * @throws BusinessLogicException
     */
    public function store()
    {
        //return $this->service->create($this->data);

        $data = explode(':', $this->data['list']);
        $newData = '';
        for ($i = 0, $j = count($data); $i < $j; $i++) {
            if ($i % 2 == 0) {
                $newData = $newData . $data[$i] . ':';
            } else {
                $newData = $newData . $data[$i] . ',';
            }
        }
        return $newData;
    }

    /**
     * 修改
     * @param $id
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function update($id)
    {
        return phpinfo();
    }

    public function destroy($id)
    {
        return $this->service->delete(['id' => $id]);
    }

    public function calDate()
    {
        return $this->service->calDate();
    }

    public function updateAll()
    {
        return $this->service->updateAll();
    }

    public function getPath()
    {
        $disk = Storage::disk('admin_print_template_public');
        $fileList = $disk->allFiles();
        $fileNameList = [];
        foreach ($fileList as $file) {
            $fileName = explode('.', $file)[0];
            $fileNameList[$fileName] = $disk->url($file);
        }
        return $fileNameList;
    }

    public function testPush()
    {
        Log::info('data', $this->data);
    }

    public function authTree()
    {
        return $this->service->authTree();
    }

    public function jPushNotify()
    {
        return $this->service->jPushNotify();
    }


}
