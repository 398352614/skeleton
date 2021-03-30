<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 3/29/2021
 * Time : 3:24 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: OrderReceiptService.php
 */


namespace App\Services\Admin;

use App\Http\Resources\Api\Admin\OrderReceiptResource;
use App\Models\OrderReceipt;

/**
 * Class OrderReceiptService
 * @package App\Services\Admin
 */
class OrderReceiptService extends BaseService
{
    /**
     * @var \string[][]
     */
    public $filterRules = [
        'order_no' => ['=', 'order_no']
    ];

    /**
     * OrderReceiptService constructor.
     * @param  OrderReceipt  $model
     * @param  null  $infoResource
     */
    public function __construct(OrderReceipt $model, $infoResource = null)
    {
        parent::__construct($model, OrderReceiptResource::class, $infoResource);
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function create($data)
    {
        $data['operator_type']  = 'admin';
        $data['operator_id']    = auth()->user()->id;

        return parent::create($data);
    }
}
