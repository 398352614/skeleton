<?php

namespace App\Http\Controllers\Api\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\Controller;
use App\Models\Version;
use App\Services\Driver\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class VersionController extends Controller
{
    /**
     * 版本检查
     * @return array
     */
    protected function check(){
        //找到最新的版本并返回
        $info =Version::query()->orderBy('created_at','desc')->first();
        return
            [
            'url'=>$info['url'],
            'version'=>$info['version'],
            'change_log'=>$info['change_log'],
            'status'=>$info['status'],
        ];
    }

    /**
     * 版本列表
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function index(){
        return Version::query()->get();
    }

    /**
     * 版本新增
     * @param Request $request
     * @throws BusinessLogicException
     */
    protected function store(Request $request){
        $url=(new UploadService)->apkUpload($request->all());
        Version::query()->create([
            'name'=>$request['name']??'TMS',
            'url'=>$url['path'],
            'version'=>$request['version'],
            'change_log'=>$request['change_log']??'',
            'status'=>$request['status']??1,
        ]);
    }

    /**
     * 版本修改
     * @param $id
     * @param Request $request
     */
    protected function update($id,Request $request){
        $info = Version::query()->where('id',$id)->first();
        Version::query()->update([
            'name'=>$request['name']??$info['name'],
            'version'=>$request['version']??$info['version'],
            'change_log'=>$request['change_log']??$info['change_log'],
            'status'=>$request['status']??$info['status'],
        ]);
    }

    /**
     * 版本删除
     * @param $id
     */
    protected function delete($id){
        $info = Version::query()->where('id',$id)->delete();
    }
}
