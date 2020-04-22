<?php
/**
 * Created by PhpStorm
 * User: Yomi
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Merchant;


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
        'type',
        'receiver',
        'receiver_phone',
        'receiver_country',
        'receiver_post_code',
        'receiver_house_number',
        'receiver_address',
        'execution_date',
        'settlement_type',
        'settlement_amount',
        'replace_amount',
        'out_order_no',
        'delivery',
        'remark',

        'item_type_1',
        'item_name_1',
        'item_number_1',
        'item_count_1',
        'item_weight_1',

        'item_type_2',
        'item_name_2',
        'item_number_2',
        'item_count_2',
        'item_weight_2',

        'item_type_3',
        'item_name_3',
        'item_number_3',
        'item_count_3',
        'item_weight_3',

        'item_type_4',
        'item_name_4',
        'item_number_4',
        'item_count_4',
        'item_weight_4',

        'item_type_5',
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
        return [
            'name' => 'templateTips.docx',
            'path' => Storage::disk('merchant_file_public')->url(auth()->user()->company_id . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'templateTips.docx')
        ];
    }

    /**
     * @return array
     * @throws BusinessLogicException
     */
    public function getTemplateExcel()
    {
        $cellData[0] =[];

        return $this->excelExport('template', OrderImportService::$headings, $cellData, 'order');
    }
}
