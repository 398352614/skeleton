<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/12/2021
 * Time : 4:50 PM
 * Email: nzl9851@88.com
 * Blog : nizer.in
 * FileName: CarMaintainService.php
 */


namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\CarMaintainResource;
use App\Models\CarMaintain;
use App\Services\BaseConstService;
use App\Traits\ExportTrait;

/**
 * Class CarMaintainService
 * @package App\Services\Admin
 */
class CarMaintainService extends BaseService
{
    use ExportTrait;

    /**
     * @var array
     */
    public $filterRules = [
        'car_no' => ['=', 'car_no'],
        'maintain_type' => ['=', 'maintain_type'],
        'is_ticket' => ['=', 'is_ticket'],
        'maintain_factory' => ['=', 'maintain_factory'],
        'maintain_date' => ['between', ['begin_date', 'end_date']],
    ];

    /**
     * 导出 Excel 头部
     * @var string[]
     */
    public $exportExcelHeader = [
        'maintain_no',
        'car_no',
        'maintain_type',
        'distance',
        'maintain_price',
        'is_ticket',
        'maintain_date',
        'maintain_factory'
    ];

    /**
     * CarMaintainService constructor.
     * @param  CarMaintain  $model
     */
    public function __construct(CarMaintain $model)
    {
        parent::__construct($model, CarMaintainResource::class);
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function create($data)
    {
        $data['operator']       = auth()->user()->fullname;
        $data['maintain_no']    = $this->getOrderNoRuleService()->createCarMaintainNO();

        foreach ($data['maintain_detail'] as $k => $v) {
            $v['maintain_no'] = $data['maintain_no'];
            $this->getCarMaintainDetailService()->create($v);
        }

        return parent::create($data);
    }

    /**
     * @param $idList
     */
    public function ticketAll($idList)
    {
        $idList = explode_id_string($idList);
        $this->query
            ->whereIn('id', $idList)
            ->update(['is_ticket' => BaseConstService::IS_TICKET_1]);
    }

    /**
     * 批量删除
     * @param $idList
     * @throws \Exception
     */
    public function destroyAll($idList)
    {
        $idList = explode_id_string($idList);

        foreach ($idList as $id) {
            $this->destroy($id);
        }
    }

    /**
     * @param  int  $id
     * @throws \Exception
     */
    public function destroy(int $id)
    {
        $item = $this->model->find($id);

        $this->getCarMaintainDetailService()
            ->query
            ->where('maintain_no', $item->maintain_no)
            ->delete();

        $item->delete();
    }

    /**
     * 导出 excel
     * @throws BusinessLogicException
     */
    public function exportExcel()
    {
        $data = $this->setFilter()->getList();

        if ($data->isEmpty()) {
            throw new BusinessLogicException('数据不存在');
        }

        $cellData = [];
        foreach ($data as $v) {
            $cellData[] = array_only_fields_sort($v, $this->exportExcelHeader);
        }
        if (empty($cellData)) {
            throw new BusinessLogicException('数据不存在');
        }

        $dir = 'carMaintainOut';
        $name = date('YmdHis') . auth()->user()->id;

        return $this->excelExport($name, $this->exportExcelHeader, $cellData, $dir);
    }
}
