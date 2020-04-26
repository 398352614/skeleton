<?php
/**
 * Created by PhpStorm
 * User: Yomi
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\OrderImportInfoResource;
use App\Http\Resources\OrderImportResource;
use App\Models\OrderImportLog;
use App\Services\BaseService;
use App\Traits\ExportTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class OrderImportService extends BaseService
{
    use ExportTrait;

    public function __construct(OrderImportLog $orderImportLog)
    {
        parent::__construct($orderImportLog, OrderImportResource::class, OrderImportInfoResource::class);
    }

    public static $headings = [
        'type_name',
        'receiver',
        'receiver_phone',
        'receiver_post_code',
        'receiver_house_number',
        'receiver_address',
        'execution_date',
        'settlement_type_name',
        'settlement_amount',
        'replace_amount',
        'out_order_no',
        'delivery_name',
        'remark',

        'item_type_name_1',
        'item_name_1',
        'item_number_1',
        'item_count_1',
        'item_weight_1',

        'item_type_name_2',
        'item_name_2',
        'item_number_2',
        'item_count_2',
        'item_weight_2',

        'item_type_name_3',
        'item_name_3',
        'item_number_3',
        'item_count_3',
        'item_weight_3',

        'item_type_name_4',
        'item_name_4',
        'item_number_4',
        'item_count_4',
        'item_weight_4',

        'item_type_name_5',
        'item_name_5',
        'item_number_5',
        'item_count_5',
        'item_weight_5',
    ];

    /**
     * 上传模板
     * @param $data
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function uploadTemplate()
    {
        $data = $this->formData;
        $data['dir'] = 'template';
        if (empty((new UploadService())->fileUpload($data))) {
            throw new BusinessLogicException('上传失败');
        };
    }

    /**
     *下载模板
     * @return mixed
     */
    public function getTemplate()
    {
        return Storage::disk('admin_file_public')->url(auth()->user()->company_id . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'order_import_template.xlsx');
    }

    /**
     * @return array
     * @throws BusinessLogicException
     */
    public function getTemplateExcel()
    {
        $cellData[0] = [];

        return $this->excelExport('template', OrderImportService::$headings, $cellData, 'order');
    }
}
