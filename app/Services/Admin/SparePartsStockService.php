<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/24/2021
 * Time : 2:01 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SparePartsStockService.php
 */


namespace App\Services\Admin;

use App\Http\Resources\Api\Admin\SparePartsStockResource;
use App\Models\SparePartsStock;

/**
 * Class SparePartsStockService
 * @package App\Services\Admin
 */
class SparePartsStockService extends BaseService
{
    /**
     * @var \string[][]
     */
    public $filterRules = [

    ];

    /**
     * @var string[]
     */
    public $orderBy = ['id' => 'desc'];

    /**
     * SparePartsStockService constructor.
     * @param  SparePartsStock  $model
     * @param  null  $infoResource
     */
    public function __construct(SparePartsStock $model, $infoResource = null)
    {
        parent::__construct($model, SparePartsStockResource::class, $infoResource);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPageList()
    {
        $this->query->join('spare_parts', 'spare_parts_stock.sp_no', '=', 'spare_parts.sp_no', 'left');

        $this->query->select(['spare_parts_stock.*', 'spare_parts.sp_name', 'spare_parts.sp_brand', 'spare_parts.sp_model', 'spare_parts.sp_unit']);

        return parent::getPageList();
    }

    /**
     * @return bool
     */
    public function setFilterRules()
    {
        if (array_key_exists('sp_name', $this->formData)) {
            $this->query->where('spare_parts.sp_name', 'like', "%".$this->formData['sp_name']."%");
        }

        if (array_key_exists('sp_no', $this->formData)) {
            $this->query->where('spare_parts.sp_no', 'like', "%".$this->formData['sp_no']."%");
        }

        return parent::setFilterRules();
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|int
     */
    public function create($data)
    {
        $check = $this->model->where('sp_no', $data['sp_no'])->first();

        if (empty($check)) {
            return parent::create($data);
        }

        return $check->increment('stock_quantity', $data['stock_quantity']);
    }
}
