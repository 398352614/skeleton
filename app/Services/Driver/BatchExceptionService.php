<?php
/**
 * 站点异常 服务
 * User: long
 * Date: 2019/12/31
 * Time: 10:54
 */
namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\BatchExceptionResource;
use App\Models\BatchException;

class BatchExceptionService extends BaseService
{
    public $orderBy = [
        'status' => 'asc',
        'created_at' => 'desc'
    ];

    public function __construct(BatchException $batchException)
    {
        parent::__construct($batchException, BatchExceptionResource::class);
    }


    /**
     * 获取详情
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info->toArray();
    }
}
