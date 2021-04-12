<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/23/2021
 * Time : 4:56 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SparePartsService.php
 */


namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\SparePartsResource;
use App\Models\SpareParts;

/**
 * Class SparePartsService
 * @package App\Services\Admin
 */
class SparePartsService extends BaseService
{
    /**
     * @var array
     */
    public $filterRules = [
        'sp_no' => ['like', 'sp_no'],
        'sp_name' => ['like', 'sp_name'],
        'created_at' => ['between', ['begin_date', 'end_date']],
    ];

    /**
     * @var string[]
     */
    public $orderBy = ['id' => 'desc'];

    /**
     * SparePartsService constructor.
     * @param  SpareParts  $model
     * @param  null  $infoResource
     */
    public function __construct(SpareParts $model, $infoResource = null)
    {
        parent::__construct($model, SparePartsResource::class, $infoResource);
    }

    public function getPageList()
    {
        $this->query->orderByDesc('id');
        return parent::getPageList();
    }

    /**
     * @param  $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function create($data)
    {
        $data['operator']   = auth()->user()->fullname;

        return parent::create($data);
    }

    /**
     * @param $where
     * @return mixed
     * @throws BusinessLogicException
     */
    public function delete($where)
    {
        $sparePart = $this->model->findOrFail($where['id']);

        $stock = $this->model->getStock($sparePart['sp_no']);

        if ($stock > 0) {
            throw new BusinessLogicException(__('该备品已有库存不能删除'));
        }

        return parent::delete($where);
    }
}
