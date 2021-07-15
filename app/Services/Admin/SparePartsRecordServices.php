<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/24/2021
 * Time : 3:39 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SparePartsRecordServices.php
 */


namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\SparePartsRecordResources;
use App\Models\SparePartsRecord;
use App\Services\BaseConstService;

/**
 * Class SparePartsRecordServices
 * @package App\Services\Admin
 */
class SparePartsRecordServices extends BaseService
{
    /**
     * @var array
     */
    public $filterRules = [
        'sp_no' => ['like', 'sp_no'],
        'receive_person' => ['like', 'receive_person'],
        'car_no' => ['like', 'car_no'],
        'receive_status' => ['=', 'receive_status'],
        'receive_date' => ['between', ['begin_date', 'end_date']],
    ];

    /**
     * @var string[]
     */
    public $orderBy = ['id' => 'desc'];

    /**
     * SparePartsRecordServices constructor.
     * @param  SparePartsRecord  $model
     * @param  null  $infoResource
     */
    public function __construct(SparePartsRecord $model, $infoResource = null)
    {
        parent::__construct($model, SparePartsRecordResources::class, $infoResource);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPageList()
    {
        $this->query->join('spare_parts', 'spare_parts_record.sp_no', '=', 'spare_parts.sp_no', 'left');

        if (array_key_exists('sp_name', $this->formData)) {
            $this->query->where('spare_parts.sp_name', 'like', "%".$this->formData['sp_name']."%");
        }

        $this->query->select(['spare_parts_record.*', 'spare_parts.sp_name', 'spare_parts.sp_brand', 'spare_parts.sp_model', 'spare_parts.sp_unit']);

        return parent::getPageList();
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws BusinessLogicException
     */
    public function create($data)
    {
        $stock = $this->getSparePartsStockService()->model->where('sp_no', $data['sp_no'])->first();

        if (empty($stock)) {
            throw new BusinessLogicException(__('该备品没有库存'));
        }

        if ($data['receive_quantity'] > $stock['stock_quantity']) {
            throw new BusinessLogicException(__('备品库存不足'));
        }

        $stock->decrement('stock_quantity', $data['receive_quantity']);

        return parent::create($data);
    }

    /**
     * 领用记录作废
     * @param  int  $id
     * @throws BusinessLogicException
     */
    public function invalid(int $id)
    {
        $record = $this->model->find($id);

        if (empty($record)) {
            throw new BusinessLogicException(__('记录不存在'));
        }

        if ($record->getOriginal('receive_status') == BaseConstService::SPARE_PARTS_RECORD_TYPE_2) {
            throw new BusinessLogicException(__('该记录已经作废'));
        }

        $stock = $this->getSparePartsStockService()->model->where('sp_no', $record['sp_no'])->first();

        $stock->increment('stock_quantity', $record['receive_quantity']);

        $record->update(['receive_status' => BaseConstService::SPARE_PARTS_RECORD_TYPE_2]);
    }
}
