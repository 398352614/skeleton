<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/15/2021
 * Time : 1:57 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: CarMaintainDetailService.php
 */


namespace App\Services\Admin;

use App\Models\CarMaintainDetail;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CarMaintainDetail
 * @package App\Services\Admin
 */
class CarMaintainDetailService extends BaseService
{
    /**
     * CarMaintainDetail constructor.
     * @param  CarMaintainDetail  $model
     * @param  null  $resource
     * @param  null  $infoResource
     */
    public function __construct(CarMaintainDetail $model, $resource = null, $infoResource = null)
    {
        parent::__construct($model, $resource, $infoResource);
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public function create($data)
    {
        return parent::create($data);
    }
}
