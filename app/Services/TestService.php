<?php
/**
 * 测试 服务
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 17:14
 */

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\TestInfoResource;
use App\Http\Resources\TestResource;
use App\Models\Test;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;


class TestService extends BaseService
{

    public $filterRules = [
        'name' => ['like', 'name']
    ];

    public function __construct(Test $test)
    {
        $this->model = $test;
        $this->query = $this->model::query();
        $this->resource = TestResource::class;
        $this->infoResource = TestInfoResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    public function show($id)
    {
        return parent::getInfo(['id' => $id], ['*'], true);
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function create($params)
    {
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('测试数据新增失败');
        }
    }

    /**
     * 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $rowCount = parent::updateById($id, [
            'name' => $data['name']
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('测试数据修改失败');
        }
    }

    public function calDate()
    {
        $startDate = '2019-12-30';
        $endDate = Carbon::parse(date('Y-m-d'));
        $startDate = Carbon::parse($startDate);
        for ($i = 1; $startDate->lte($endDate); $i++) {
            $startDate = $startDate->modify("+1 days");
            $startDate = $startDate->format('Y-m-d');
        }
    }

    public function updateAll()
    {

    }

}
