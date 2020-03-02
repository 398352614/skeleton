<?php
/**
 * 导出类
 */

namespace App\Traits;

use App\Exceptions\BusinessLogicException;
use App\Exports\BatchListExport;
use App\Exports\MerchantExport;
use App\Models\Merchant;
use App\Services\BaseService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

trait ExportTrait
{
    protected $txtDisk;

    public function __construct()
    {
        $this->txtDisk = Storage::disk('admin_image_public');
    }

    /**
     * 表格导出
     * @param $name
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function excelExport($name,$params,$dir)
    {
        $subPath = auth()->user()->company_id . DIRECTORY_SEPARATOR . $dir;
        $name = date('Ymd') . $name;
        $path ='public\\admin\\excel\\'.$subPath . DIRECTORY_SEPARATOR . $name.'.xlsx';
        try {
            if($dir==='tour'){
                $rowCount=Excel::store(new BatchListExport($name,$params),$path);
            }
            if($dir==='merchant'){
                $rowCount=Excel::store(new MerchantExport($params),$path);
            }
        } catch (\Exception $ex) {
            throw new BusinessLogicException('表格上传失败,请重新操作');
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('表格上传失败,请重新操作');
        }
        return [
            'name' => $name.'.xlsx',
            'path' => Storage::disk('admin_excel_public')->url($subPath . DIRECTORY_SEPARATOR . $name.'.xlsx')
        ];
    }

    /**
     * 文档导出
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function txtExport($name,$params,$dir){
        $subPath = auth()->user()->company_id . DIRECTORY_SEPARATOR . $dir;
        $params['name'] = date('Ymd') . $params['name'].'.txt';
        try {
            $rowCount = $this->txtDisk->put($subPath.DIRECTORY_SEPARATOR.$params['name'],$params['txt']);
        } catch (\Exception $ex) {
            throw new BusinessLogicException('文档上传失败,请重新操作');
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('文档上传失败,请重新操作');
        }
        return [
            'name' => $params['name'],
            'path' => $this->txtDisk->url($subPath . DIRECTORY_SEPARATOR . $params['name'])
        ];
    }
}
