<?php
/**
 * 导出类
 */

namespace App\Traits;

use App\Exceptions\BusinessLogicException;
use App\Exports\AddressExport;
use App\Exports\BaseExport;
use App\Exports\BillVerifyExport;
use App\Exports\MerchantOrderExport;
use App\Exports\OrderExport;
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
            for ($i = 0, $j = count($headings); $i < $j; $i++) {
                foreach ($headings[$i] as $k => $v) {
                    if ($dir == 'plan') {
                        if (in_array($v, array_keys(__('excel.' . $dir)))) {
                            $headings[$i][$k] = __('excel.' . $dir . '.' . $v);
                        }
                    } else {
                        $a = __('excel.' . $dir . '.' . $i);
                        if (!empty($a[$v])) {
                            $headings[$i][$k] = $a[$v];
                        } else {
                            $unit = '(' . ConstTranslateTrait::currencyUnitTypeSymbol(CompanyTrait::getCompany()['currency_unit']) . ')';
                            $headings[$i][$k] = $v . $unit;
                        }
                        if ($dir == 'order') {
                            $headings[$i][$k] = $headings[$i][$k] . $this->getUnit($v);
                        }
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
        if ($dir == 'batchCount') {
            $headings[1] = $this->translate($headings[1], $dir);
        }
        $headings = $this->translate($headings, $dir);
        $subPath = auth()->user()->company_id . DIRECTORY_SEPARATOR . $dir;
        $path = 'public\\admin\\excel\\' . $subPath . DIRECTORY_SEPARATOR . md5($name.now()) . '.xlsx';
        try {
            if ($dir == 'plan') {
                $rowCount = Excel::store(new PlanExport($data, $headings, $name, $dir, $params), $path);
            } elseif ($dir == 'order') {
                $rowCount = Excel::store(new OrderExport($data, $headings, $name, $dir), $path);
            } elseif ($dir == 'merchantOrder') {
                $rowCount = Excel::store(new MerchantOrderExport($data, $headings, $name, $dir), $path);
            } elseif ($dir == 'addressExcelExport') {
                $rowCount = Excel::store(new AddressExport($data, $headings, $name, $dir), $path);
            } elseif ($dir == 'billVerify') {
                $rowCount = Excel::store(new BillVerifyExport($data, $headings, $name, $dir), $path);
            } else {
                $rowCount = Excel::store(new BaseExport($data, $headings, $name, $dir), $path);
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

    public function getUnit($heading)
    {
        $currency = [
            "amount_1",
            "amount_2",
            "amount_3",
            "amount_4",
            "amount_5",
            "amount_6",
            "amount_7",
            "amount_8",
            "amount_9",
            "amount_10",
            "amount_11",
            'settlement_amount'
        ];
        $weight = [
            "package_weight_1",
            "package_weight_2",
            "package_weight_3",
            "package_weight_4",
            "package_weight_5",

            "material_weight_1",
            "material_weight_2",
            "material_weight_3",
            "material_weight_4",
            "material_weight_5",
        ];
        $volume = [

            "material_size_1",
            "material_size_2",
            "material_size_3",
            "material_size_4",
            "material_size_5",
        ];
        if (in_array($heading, $currency)) {
            $unit = '(' . ConstTranslateTrait::currencyUnitTypeSymbol(CompanyTrait::getCompany()['currency_unit']) . ')';
        } elseif (in_array($heading, $weight)) {
            $unit = '(' . ConstTranslateTrait::weightUnitTypeSymbol(CompanyTrait::getCompany()['weight_unit']) . ')';
        } elseif (in_array($heading, $volume)) {
            $unit = '(' . ConstTranslateTrait::volumeUnitTypeSymbol(CompanyTrait::getCompany()['volume_unit']) . ')';
        } else {
            $unit = '';
        }
        return $unit;
    }
}
