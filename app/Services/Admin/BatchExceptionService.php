<?php
/**
 * 异常管理 服务
 * User: long
 * Date: 2020/1/3
 * Time: 16:26
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\BatchExceptionResource;
use App\Models\BatchException;
use App\Services\BaseConstService;
use Illuminate\Support\Carbon;

class BatchExceptionService extends BaseService
{
    public $filterRules = [
        'status' => ['=', 'status'],
        'batch_exception_no,batch_no' => ['like', 'keyword'],
        'created_at' => ['between', ['begin_date', 'end_date']]
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
            'deal_remark' => $params['deal_remark'],
            'status' => BaseConstService::BATCH_EXCEPTION_2,
            'deal_time' => Carbon::now()
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('异常处理失败，请重新操作');
        }
        //获取是否还有相同站点存在异常未处理的情况,若不存在,则更新站点异常标签和更新订单异常标签
        $dbInfo = parent::getInfo(['batch_no' => $info['batch_no'], 'status' => BaseConstService::BATCH_EXCEPTION_1], ['id'], false);
        if (!empty($dbInfo)) return;

        //更新站点异常状态(异常->正常)
        $rowCount = $this->getBatchService()->update(['batch_no' => $info['batch_no']], ['exception_label' => BaseConstService::BATCH_EXCEPTION_LABEL_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('异常处理失败，请重新操作');
        }

        //更新订单异常状态(异常->正常)
        $rowCount = $this->getTrackingOrderService()->update(['batch_no' => $info['batch_no']], ['exception_label' => BaseConstService::BATCH_EXCEPTION_LABEL_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('异常处理失败，请重新操作');
        }
    }
}
