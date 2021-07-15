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
        'maintain_factory' => ['like', 'maintain_factory'],
        'maintain_date' => ['between', ['begin_date', 'end_date']],
    ];

    /**
     * @var string[]
     */
    public $orderBy = ['id' => 'desc'];

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
        $data['maintain_no']    = $this->getOrderNoRuleService()->createCarMaintainNo();

        $this->saveDetail($data['maintain_detail'], $data['maintain_no']);

        return parent::create($data);
    }

    /**
     * @param $where
     * @param  string[]  $selectFields
     * @param  bool  $isResource
     * @param  array  $orderFields
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getInfo($where, $selectFields = ['*'], $isResource = true, $orderFields = [])
    {
        $data = parent::getInfo($where, $selectFields, $isResource, $orderFields);

        if (empty($data)) {
            throw new BusinessLogicException(__('数据不存在'));
        }

        $data['maintain_detail'] = $this->getCarMaintainDetailService()
                ->model->where('maintain_no', $data['maintain_no'])
                ->get()->toArray() ?? [];

        $data['material_price_total'] = 0;
        $data['hour_price_total'] = 0;

        foreach ($data['maintain_detail'] as $v) {
            $data['material_price_total'] += $v['material_price'];
            $data['hour_price_total'] += $v['hour_price'];
        }

        $data['maintain_type_name'] = $this->model->getMaintainType($data['maintain_type']);
        $data['is_ticket_name'] = $this->model->getIsTicket($data['is_ticket']);

        return $data;
    }

    /**
     * @param $where
     * @param $data
     * @return int
     * @throws \Exception
     */
    public function update($where, $data)
    {
        $item = $this->model->findOrFail($where['id']);

        $this->deleteDetail($item['maintain_no']);
        $this->saveDetail($data['maintain_detail'], $item['maintain_no']);

        return parent::update($where, $data);
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
        $item = $this->model->findOrFail($id);

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
    public function exportExcel($idList)
    {
        $idList = explode_id_string($idList);

        $data = $this->query->whereIn('id', $idList)->get();

        if ($data->isEmpty()) {
            throw new BusinessLogicException(__('数据不存在'));
        }

        $cellData = [];
        foreach ($data->toArray() as $v) {
            $cellData[] = array_only_fields_sort($v, $this->exportExcelHeader);
        }
        if (empty($cellData)) {
            throw new BusinessLogicException(__('数据不存在'));
        }

        $dir = 'carMaintainOut';
        $name = date('YmdHis') . auth()->user()->id;

        return $this->excelExport($name, $this->exportExcelHeader, $cellData, $dir);
    }

    /**
     * @param  array  $detail
     * @param  string  $no
     */
    protected function saveDetail(array $detail, string $no)
    {
        foreach ($detail as $v) {
            $v['maintain_no'] = $no;
            $this->getCarMaintainDetailService()->create($v);
        }
    }

    /**
     * @param  string  $no
     * @throws \Exception
     */
    protected function deleteDetail(string $no)
    {
        $this->getCarMaintainDetailService()->model->where('maintain_no', $no)->delete();
    }
}
