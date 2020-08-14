<?php


namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\ApiTimesResource;
use App\Models\ApiTimes;
use App\Services\BaseService;
use Illuminate\Support\Carbon;

class ApiTimesService extends BaseService
{
    public $filterRules = [
        'date' => ['between', ['begin_date', 'end_date']],
        'company_id' => ['=', 'company_id'],
    ];

    public function __construct(ApiTimes $model)
    {
        parent::__construct($model,ApiTimesResource::class,ApiTimesResource::class);
    }

    /**
     * 请求数查询
     * @return array|\Illuminate\Database\Eloquent\Collection
     * @throws BusinessLogicException
     */
    public function getPageList()
    {
        $info['detail_list'] = parent::getPageList()->toArray(request());
        $columns = ['directions_times', 'actual_directions_times', 'api_directions_times', 'distance_times', 'actual_distance_times', 'api_distance_times'];
        foreach ($columns as $v) {
            $info[$v] = 0;
        }
        if(!empty($info['detail_list'])){
            throw new BusinessLogicException('数据不存在');
        }
        foreach ($info['detail_list'] as $k => $v) {
            foreach ($columns as $x) {
                $info[$x] += $v[$x];
            }
        }
        $info = array_only_fields_sort($info, array_merge($columns, ['detail_list']));
        return $info;
    }

    /**统计请求数
     * @param $type
     */
    public function timesCount($type)
    {
        $date = Carbon::today()->format('Y-m-d');
        $companyId = auth()->user()->company_id;
        if (empty(parent::getInfo(['date' => $date, 'company_id' => $companyId], ['*'], false))) {
            parent::create([
                'date' => $date,
                'company_id' => $companyId,
                $type => 1
            ]);
        } else {
            $this->query->where('date', $date)->where('company_id', $companyId)->increment($type);
        }
    }
}
