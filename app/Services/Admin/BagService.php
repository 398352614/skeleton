<?php


namespace App\Http\Controllers\Api\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\BagResource;
use App\Models\Bag;
use App\Services\Admin\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BagService extends BaseService
{
    public $filterRules = [
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'driver_name' => ['like', 'driver_name'],
        'driver_id' => ['=', 'driver_id'],
        'line_id,line_name' => ['like', 'line_keyword'],
        'batch_no' => ['like', 'keyword'],
        'place_fullname' => ['=', 'place_fullname'],
        'place_phone' => ['=', 'place_phone'],
        'place_country' => ['=', 'place_country'],
        'place_post_code' => ['=', 'place_post_code'],
        'place_house_number' => ['=', 'place_house_number'],
        'place_city' => ['=', 'place_city'],
        'place_street' => ['=', 'place_street'],
        'tour_no' => ['like', 'tour_no']
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(Bag $model)
    {
        parent::__construct($model, BagResource::class );
    }

    /**
     * 查询
     * @return Builder[]|Collection|AnonymousResourceCollection
     */
    public function getPageList()
    {
        return parent::getList();
    }

    /**
     * 详情
     * @param $id
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }

    /**
     * 新增
     * @param array $data
     * @throws BusinessLogicException
     */
    public function store(array $data)
    {
        $rowCount = parent::create($data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * 通过ID 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $this->check($data);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }

    /**
     * 验证
     * @param $data
     */
    public function check(&$data)
    {

    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
    }

    public function addPackage(array $data)
    {
    }
}
