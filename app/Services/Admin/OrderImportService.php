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

    protected $headings = [
        'execution_date',
        'out_order_no',
        'express_first_no',
        'express_second_no',
        'merchant',
        'nature',
        'settlement_type',
        'settlement_amount',
        'replace_amount',
        'delivery',
        'receiver',
        'receiver_phone',
        'receiver_country',
        'receiver_post_code',
        'receiver_house_number',
        'receiver_city',
        'receiver_street',
        'receiver_address',
        'sender',
        'sender_phone',
        'sender_country',
        'sender_post_code',
        'sender_house_number',
        'sender_city',
        'sender_street',
        'sender_address',
        'special_remark',
        'remark',
        'package_list',
        'material_list',
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
        $cellData[0] = array_values([
            'execution_date' => '2020-01-01',
            'out_order_no' => '634831',
            'express_first_no' => 'ERP204231834534',
            'express_second_no' => '223423',
            'merchant' => 'ERP',
            'nature' => '1',
            'settlement_type' => '1',
            'settlement_amount' => '5.00',
            'replace_amount' => '10.00',
            'delivery' => '1',
            'receiver' => 'Mike',
            'receiver_phone' => '512353',
            'receiver_country' => 'NL',
            'receiver_post_code' => '21535PJ',
            'receiver_house_number' => '20',
            'receiver_city' => 'Nieuw-Vennep',
            'receiver_street' => 'Pesetaweg',
            'receiver_address' => 'First floor',
            'sender' => 'Jack',
            'sender_phone' => '17558493213',
            'sender_country' => '中国',
            'sender_post_code' => '410000',
            'sender_house_number' => 'C8',
            'sender_city' => '长沙',
            'sender_street' => '麓谷企业广场',
            'sender_address' => '808',
            'special_remark' => 'Special remark 1',
            'remark' => 'remark 1',
            'package_list' => '[     
                {        
                        "name":"包裹1",
                        "express_first_no":"express_no_1",
                        "express_second_no":"express_no_2", 
                        "out_order_no":"12345",
                        "weight":"12.12",
                        "quantity":"12", 
                        "remark":"备注"   
                    },
                    {        
                        "name":"package 1",
                        "express_first_no":"express_no_3",
                        "express_second_no":"express_no_4", 
                        "out_order_no":"1234567",
                        "weight":"12.12",
                        "quantity":"12", 
                        "remark":"remark 2"   
                    }
                ]',
            'material_list' => '[     
                    {        
                        "name":"材料1", 
                        "code":"code1", 
                        "out_order_no":"122121",
                        "expect_quantity":"3",
                        "remark":"备注"
                    },
                    {        
                        "name":"material 1", 
                        "code":"code2", 
                        "out_order_no":"512352",
                        "expect_quantity":"2",
                        "remark":"remark 3"
                    }
                ]',
        ]);

        return $this->excelExport('template', $this->headings, $cellData, 'order');
    }
}
