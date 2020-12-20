<?php
/**
 * 导出类
 */

namespace App\Traits;

use App\Exceptions\BusinessLogicException;
use App\Exports\BaseExport;
use App\Exports\PlanExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

trait ExportTrait
{
    protected $txtDisk;

    public function __construct()
    {
        $this->txtDisk = Storage::disk('admin_image_public');
    }

    public function translate($headings, $dir)
    {
        if (is_array($headings[0])) {
            $systemHeadings = array_keys(__('excel.plan'));
            for ($i = 0, $j = count($headings); $i < $j; $i++) {
                for ($k = 0, $l = count($headings[$i]); $k < $l; $k++) {
                    if (in_array($headings[$i][$k], $systemHeadings)) {
                        $headings[$i][$k] = __('excel.' . $dir . '.' . $headings[$i][$k]);
                    }
                }
            }
        } else {
            for ($i = 0, $j = count($headings); $i < $j; $i++) {
                $headings[$i] = __('excel.' . $dir . '.' . $headings[$i]);
            }
        }
        return $headings;
    }

    /**
     * 表格导出
     * @param $name
     * @param $headings
     * @param $data
     * @param $dir
     * @param array $params
     * @return array
     * @throws BusinessLogicException
     */
    public function excelExport($name, $headings, $data, $dir, $params = [])
    {
        if($dir=='batchCount'){
            $headings[1]=$this->translate($headings[1],$dir);
        }
        $headings = $this->translate($headings, $dir);
        $subPath = auth()->user()->company_id . DIRECTORY_SEPARATOR . $dir;
        $path = 'public\\admin\\excel\\' . $subPath . DIRECTORY_SEPARATOR . md5($name) . '.xlsx';
        try {
            if ($dir == 'plan') {
                $rowCount = Excel::store(new PlanExport($data, $headings, $name, $dir, $params), $path);
            } else {
                Log::info('开始时间'.Carbon::now()->format('Y-m-d'));
                $rowCount = Excel::store(new BaseExport($data, $headings, $name, $dir), $path);
                Log::info('结束时间'.Carbon::now()->format('Y-m-d'));
            }
        } catch (\Exception $ex) {
            throw new BusinessLogicException('表格导出失败，请重新操作');
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('表格导出失败，请重新操作');
        }
        return [
            'name' => md5($name) . '.xlsx',
            'path' => Storage::disk('admin_excel_public')->url($subPath . DIRECTORY_SEPARATOR . md5($name) . '.xlsx')
        ];
    }

    /**
     * 文档导出
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function txtExport($name, $params, $dir)
    {
        $subPath = auth()->user()->company_id . DIRECTORY_SEPARATOR . $dir;
        $params['name'] = date('Ymd') . $params['name'] . '.txt';
        try {
            $rowCount = $this->txtDisk->put($subPath . DIRECTORY_SEPARATOR . $params['name'], $params['txt']);
        } catch (\Exception $ex) {
            throw new BusinessLogicException('文档上传失败，请重新操作');
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('文档上传失败，请重新操作');
        }
        return [
            'name' => $params['name'],
            'path' => $this->txtDisk->url($subPath . DIRECTORY_SEPARATOR . $params['name'])
        ];
    }
}
