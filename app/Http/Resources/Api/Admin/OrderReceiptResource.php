<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 3/29/2021
 * Time : 3:16 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: OrderReceiptResource.php
 */


namespace App\Http\Resources\Api\Admin;




use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderReceiptResource
 * @package App\Http\Resources\Api\Admin
 */
class OrderReceiptResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $operator = $this->getOperator($this->operator_id, $this->operator_type);

        return parent::toArray($request) + ['operator' => $operator];
    }
}
