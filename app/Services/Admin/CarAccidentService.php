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
        $data['accident_no'] = $this->getOrderNoRuleService()->createCarAccidentNO();

        return parent::create($data);
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
}
