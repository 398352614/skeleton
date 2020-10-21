<?php


namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\AdditionalPackageResource;
use App\Models\AdditionalPackage;
use App\Services\Admin\BaseService;
use App\Traits\SearchTrait;
use Illuminate\Support\Arr;

class AdditionalPackageService extends BaseService
{
    public $filterRules = [
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'merchant_id' => ['=', 'merchant_id'],
        'package_no' => ['like', 'package_no'],
    ];

    public function __construct(AdditionalPackage $model)
    {
        parent::__construct($model, AdditionalPackageResource::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function getPageList()
    {
        parent::buildQuery($this->query, $this->filters);
        $info=$this->query->get();
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $batchNo = $info->pluck('batch_no')->toArray();
        $info = $this->getBatchService()->getAdditionalPackageList($batchNo);
        foreach ($info as $k => $v) {
            $info[$k]['additional_package_list'] = parent::getList(['batch_no' => $v['batch_no']]);
            $info[$k]['additional_package_count'] = parent::count(['batch_no' => $v['batch_no']]);
        }
        return $info;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $batch = $this->getBatchService()->getInfo(['id' => $id]);
        if (empty($batch)) {
            throw new BusinessLogicException('数据不存在');
        }
        return parent::getList(['batch_no' => $batch['batch_no']]);
    }
}
