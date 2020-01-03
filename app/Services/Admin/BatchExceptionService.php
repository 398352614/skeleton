<?php
/**
 * 异常管理 服务
 * User: long
 * Date: 2020/1/3
 * Time: 16:26
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\BatchResource;
use App\Models\BatchException;
use App\Services\BaseConstService;
use App\Services\BaseService;

class BatchExceptionService extends BaseService
{

    public $filterRules = [
        'status' => ['=', 'status'],
        'batch_exception_no' => ['like', 'keyword'],
        'created_at' => ['between', ['begin_date', 'end_date']]
    ];

    public function __construct(BatchException $batchException)
    {
        $this->request = request();
        $this->model = $batchException;
        $this->query = $this->model::query();
        $this->resource = BatchResource::class;
        $this->formData = $this->request->all();
        $this->setFilterRules();
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

    /**
     * 处理
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function deal($id, $params)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        if (intval($info['status']) !== BaseConstService::BATCH_EXCEPTION_1) {
            throw new BusinessLogicException('当前状态不能处理异常');
        }
        $rowCount = parent::updateById($id, [
            'deal_id' => auth()->id(),
            'deal_name' => auth()->user()->username,
            'deal_remark' => $params['deal_remark']
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('处理失败,请重新操作');
        }
    }

}