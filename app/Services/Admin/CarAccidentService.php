<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/10/2021
 * Time : 4:37 PM
 * Email: nzl9851@88.com
 * Blog : nizer.in
 * FileName: CarAccidentService.php
 */


namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\CarAccidentResource;
use App\Models\CarAccident;

/**
 * Class CarAccidentService
 * @package App\Services\Admin
 */
class CarAccidentService extends BaseService
{
    /**
     * @var array
     */
    public $filterRules = [
        'car_no' => ['=', 'car_no'],
        'accident_duty' => ['=', 'accident_duty'],
        'deal_type' => ['=', 'deal_type'],
        'accident_date' => ['between', ['begin_date', 'end_date']],
    ];

    /**
     * @var string[]
     */
    public $orderBy = ['id' => 'desc'];

    /**
     * CarAccidentService constructor.
     * @param  CarAccident  $model
     */
    public function __construct(CarAccident $model)
    {
        parent::__construct($model, CarAccidentResource::class);
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function create($data)
    {
        $data['operator'] = auth()->user()->fullname;
        $data['accident_no'] = $this->getOrderNoRuleService()->createCarAccidentNo();
        $data['insurance_date'] = empty($data['insurance_date']) ? null : $data['insurance_date'];

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
        $data = parent::getInfo($where, $selectFields, $isResource);

        if (empty($data)) {
            throw new  BusinessLogicException(__('数据不存在'));
        }

        return array_merge($data->toArray(), [
            'deal_type_name' => $this->model->getDealType($data['deal_type']),
            'accident_duty_name' => $this->model->getAccidentDuty($data['accident_duty']),
            'insurance_indemnity_name' => $this->model->getInsuranceIndemnity($data['insurance_indemnity'])
        ]);
    }

    /**
     * 批量删除
     * @param $idList
     * @throws \Exception
     */
    public function destroyAll($idList)
    {
        $idList = explode_id_string($idList);
        $this->query->whereIn('id', $idList)->delete();
    }

    public function getPageList()
    {
        $this->query->orderByDesc('id');
        return parent::getPageList();
    }
}
