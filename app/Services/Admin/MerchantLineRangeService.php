<?php
/**
 * 商户线路范围 服务
 * User: long
 * Date: 2020/8/13
 * Time: 13:46
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\FeeResource;
use App\Models\Fee;
use App\Models\MerchantLineRange;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class MerchantLineRangeService extends BaseService
{
    public function __construct(MerchantLineRange $model)
    {
        parent::__construct($model, null);
    }

    /**
     * 线路服务
     * @return LineService
     */
    public function getLineService()
    {
        return self::getInstance(LineService::class);
    }

    /**
     * 线路范围服务
     * @return LineRangeService
     */
    public function getLineRangeService()
    {
        return self::getInstance(LineRangeService::class);
    }

    /**
     * 商户 服务
     * @return MerchantService
     */
    public function getMerchantService()
    {
        return self::getInstance(MerchantService::class);
    }

    /**
     * 获取商户线路服务范围
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        /**********************************************1.获取线路*******************************************************/
        $line = $this->getLineService()->getInfo(['id' => $id], ['id', 'name'], false);
        if (empty($line)) {
            throw new BusinessLogicException('数据不存在');
        }
        $line = $line->toArray();
        /*******************************************2.获取线路范围*****************************************************/
        $lineRangeList = $this->getLineRangeService()->getList(['line_id' => $id], ['post_code_start', 'post_code_end', 'schedule'], false);
        $lineRangeList = $lineRangeList->groupBy(function ($lineRange) {
            return $lineRange['post_code_start'] . '-' . $lineRange['post_code_end'];
        })->map(function ($detailLineRangeList) {
            $detailLineRangeList = $detailLineRangeList->toArray();
            $detailLineRangeList = [
                'post_code_range' => $detailLineRangeList[0]['post_code_start'] . '-' . $detailLineRangeList[0]['post_code_end'],
                'workday_list' => array_column($detailLineRangeList, 'schedule')
            ];
            return $detailLineRangeList;
        })->toArray();
        /*******************************************3.获取商户线路范围*************************************************/
        $merchantLineRangeList = parent::getList(['line_id' => $id], ['*'], false)->toArray();
        $merchantIdList = array_unique(array_column($merchantLineRangeList, 'merchant_id'));
        $merchantList = $this->getMerchantService()->getList(['id' => ['in', $merchantIdList]], ['id', 'name'], false)->toArray();
        $merchantList = array_create_index($merchantList, 'id');
        /**********************************************4.数据填充******************************************************/
        $merchantLineRangeList = collect($merchantLineRangeList)->map(function ($merchantLineRange, $key) use ($merchantList) {
            $merchantLineRange['merchant_id_name'] = $merchantList[$merchantLineRange['merchant_id']]['name'];
            $merchantLineRange['post_code_range'] = $merchantLineRange['post_code_start'] . '-' . $merchantLineRange['post_code_end'];
            return collect($merchantLineRange);
        })->groupBy('post_code_range')->toArray();
        foreach ($merchantLineRangeList as $postCodeRange => $postCodeRangeList) {
            $newPostCodeRangeList = collect($postCodeRangeList)->groupBy('merchant_id')->map(function ($merchantRangeList) {
                $merchantRangeList = $merchantRangeList->toArray();
                $newMerchantRange = Arr::only($merchantRangeList[0], ['id', 'company_id', 'merchant_id', 'merchant_id_name', 'line_id', 'is_alone']);
                $newMerchantRange['workday_list'] = array_column($merchantRangeList, 'schedule');
                return collect($newMerchantRange);
            })->toArray();
            $lineRangeList[$postCodeRange]['merchant_list'] = array_values($newPostCodeRangeList);
        }
        data_fill($lineRangeList, '*.merchant_list', []);
        $line['merchant_line_range_list'] = array_values($lineRangeList);
        return $line;
    }

    /**
     * 更新
     * @param $id
     * @param $data
     * @throws BusinessLogicException
     */
    public function createOrUpdate($id, $data)
    {
        $merchantLineRangeList = $data['merchant_line_range_list'];
        $line = $this->getLineService()->getInfo(['id' => $id], ['id', 'name'], false);
        if (empty($line)) {
            throw new BusinessLogicException('数据不存在');
        }
        //获取商户列表
        $merchantIdList = array_unique(array_column($merchantLineRangeList, 'merchant_id'));
        $merchantList = $this->getMerchantService()->getList(['id' => ['in', $merchantIdList]], ['id', 'name'], false)->toArray();
        $merchantList = array_create_index($merchantList, 'id');
        //获取线路范围列表
        $lineRangeList = $this->getLineRangeService()->getList(['line_id' => $id], ['*'], false);
        $lineRangeList = $lineRangeList->groupBy(function ($lineRange) {
            return $lineRange['post_code_start'] . '-' . $lineRange['post_code_end'];
        })->map(function ($detailLineRangeList) {
            $detailLineRangeList = $detailLineRangeList->toArray();
            $workdayList = implode(',', array_column($detailLineRangeList, 'schedule'));
            return collect([
                'line_id' => $detailLineRangeList[0]['line_id'],
                'post_code_start' => $detailLineRangeList[0]['post_code_start'],
                'post_code_end' => $detailLineRangeList[0]['post_code_end'],
                'country' => $detailLineRangeList[0]['country'],
                'workday_list' => $workdayList
            ]);
        })->toArray();
        //验证线路范围是否存在
        $merchantLineRangeList = collect($merchantLineRangeList)->filter(function ($merchantLineRange) use ($lineRangeList, $merchantList) {
            return !empty($lineRangeList[$merchantLineRange['post_code_range']]) && !empty($merchantList[$merchantLineRange['merchant_id']]);
        })->unique(function ($merchantLineRange) {
            return $merchantLineRange['merchant_id'] . '-' . $merchantLineRange['post_code_range'];
        })->map(function ($merchantLineRange) use ($lineRangeList) {
            $merchantLineRange['line_id'] = $lineRangeList[$merchantLineRange['post_code_range']]['line_id'];
            $merchantLineRange['country'] = $lineRangeList[$merchantLineRange['post_code_range']]['country'];
            $merchantLineRange['workday_list'] = implode(',', array_intersect(explode_id_string($merchantLineRange['workday_list']), explode_id_string($lineRangeList[$merchantLineRange['post_code_range']]['workday_list'])));
            return collect($merchantLineRange);
        })->toArray();
        $merchantLineRangeList = array_values($merchantLineRangeList);

        //删除线路的商户线路范围
        $rowCount = parent::delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //新增线路的商户线路范围
        foreach ($merchantLineRangeList as $merchantLineRange) {
            $workdayList = explode(',', $merchantLineRange['workday_list']);
            $newList = [];
            foreach ($workdayList as $key => $workday) {
                $newList[$key] = $merchantLineRange;
                $newList[$key]['schedule'] = $workday;
                list($newList[$key]['post_code_start'], $newList[$key]['post_code_end']) = explode('-', $newList[$key]['post_code_range']);
                unset($newList[$key]['post_code_range'], $newList[$key]['workday_list']);
            }
            $rowCount = parent::insertAll($newList);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败');
            }
        }
    }

    /**
     * 批量新增范围
     * @param $lineId
     * @param $rangeList
     * @param $workdayList
     * @param $country
     * @throws BusinessLogicException
     */
    public function storeRangeList($lineId, $rangeList, $workdayList, $country)
    {
        //删除商户线路范围-不在取派日期中的
        $rowCount = parent::delete(['line_id' => $lineId, 'schedule' => ['not in', $workdayList]]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //删除商户线路范围-不在邮编范围内的
        $postCodeRangeList = [];
        foreach ($rangeList as $key => $range) {
            $postCodeRangeList[] = $range['post_code_start'] . '-' . $range['post_code_end'];
        }
        $rowCount = $this->model->newQuery()->where('line_id', $lineId)->whereNotIn(DB::raw("CONCAT(post_code_start,'-',post_code_end)"), $postCodeRangeList)->delete();
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //获取新增的取派日期列表
        $merchantLineRangeList = parent::getList(['line_id' => $lineId], ['*'], false)->toArray();
        $dbWorkdayList = array_unique(array_column($merchantLineRangeList, 'schedule'));
        $diffWorkdayList = array_diff($workdayList, $dbWorkdayList);
        if (!empty($diffWorkdayList)) {
            $merchantLineRangeList = collect($merchantLineRangeList)->groupBy(function ($merchantLineRange) {
                return $merchantLineRange['post_code_start'] . '-' . $merchantLineRange['post_code_end'];
            })->map(function ($detailMerchantLineRangeList) {
                $detailMerchantLineRangeList = $detailMerchantLineRangeList->toArray();
                return collect(Arr::only($detailMerchantLineRangeList[0], ['merchant_id', 'line_id', 'post_code_start', 'post_code_end', 'country', 'is_alone']));
            })->toArray();
            $dataList = [];
            foreach ($diffWorkdayList as $workday) {
                foreach ($merchantLineRangeList as $MerchantRange) {
                    $dataList[] = array_merge($MerchantRange, ['schedule' => $workday]);
                }
            }
            $rowCount = parent::insertAll($dataList);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败');
            }
        }
        //新增新的邮编的所有商户范围
        $merchantPostCodeRangeList = [];
        $merchantLineRangeList = parent::getList(['line_id' => $lineId], ['post_code_start', 'post_code_end'], false, ['post_code_start', 'post_code_end']);
        foreach ($merchantLineRangeList as $merchantLineRange) {
            $merchantPostCodeRangeList[] = $merchantLineRange['post_code_start'] . '-' . $merchantLineRange['post_code_end'];
        }
        $diffPostCodeRangeList = array_diff($postCodeRangeList, $merchantPostCodeRangeList);
        if (empty($diffPostCodeRangeList)) return;
        $merchantList = $this->getMerchantService()->getList([], ['*'], false)->toArray();
        if (empty($merchantList)) return;
        $insetRangeList = [];
        foreach ($merchantList as $merchant) {
            foreach ($diffPostCodeRangeList as $postCodeRange) {
                list($postCodeStart, $postCodeEnd) = explode('-', $postCodeRange);
                foreach ($workdayList as $schedule) {
                    $insetRangeList[] = [
                        'merchant_id' => $merchant['id'],
                        'line_id' => $lineId,
                        'post_code_start' => $postCodeStart,
                        'post_code_end' => $postCodeEnd,
                        'schedule' => $schedule,
                        'country' => $country
                    ];
                }
            }
        }
        $rowCount = parent::insertAll($insetRangeList);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }
}
