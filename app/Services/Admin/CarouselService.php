<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\CarouselResource;
use App\Models\Carousel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class CarouselService extends BaseService
{
    public function __construct(Carousel $address)
    {
        parent::__construct($address, CarouselResource::class);
    }

    public $filterRules = [

    ];

    public $orderBy = [
        'sort_id' => 'asc',
    ];

    /**
     * 获取详情
     * @param $id
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }


    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        unset($params['rolling_time']);
        if ($this->count() > 6) {
            throw new BusinessLogicException('最多不得超过6张');
        }
        $dbData=parent::getInfo(['company_id'=>auth()->user()->company_id],['*'],false);
        if(!empty($dbData)){
            $params['rolling_time']=$dbData['rolling_time'];
        }
        $rowCount = parent::create($params);
        if ($rowCount == false) {
            throw new BusinessLogicException('新增失败，请重新操作');
        }
    }

    /**
     * 通过ID 修改
     * @param $id
     * @param $data
     * @return void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        unset($data['rolling_time']);
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $rowCount = parent::updateById($id, $data);
        if ($rowCount == false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount == false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
    }

    /**
     * 排序
     * @param $data
     * @throws BusinessLogicException
     */
    public function updateSort($data)
    {
        $ids = explode_id_string($data['id_list']);
        foreach ($ids as $k => $v) {
            parent::updateById($v, ['sort_id' => $k]);
        }
        $row = parent::update(['company_id' => auth()->user()->company_id], ['rolling_time' => $data['rolling_time']]);
        if ($row == false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }

}
