<?php
/**
 * 备忘录 服务
 * User: long
 * Date: 2019/12/26
 * Time: 14:10
 */

namespace App\Services\Driver;


use App\Exceptions\BusinessLogicException;
use App\Models\Memorandum;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class MemorandumService extends BaseService
{
    public function __construct(Memorandum $memorandum)
    {
        $this->request = request();
        $this->model = $memorandum;
        $this->query = $this->model::query();
    }


    public function store($params)
    {
        $rowCount = parent::create(array_push($params, ['driver_id' => auth()->id()]));
        if ($rowCount === false) {
            throw new BusinessLogicException('备忘录新增失败!');
        }
    }

}