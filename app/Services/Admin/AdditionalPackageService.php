<?php


namespace App\Services\Admin;


use App\Http\Resources\AdditionalPackageResource;
use App\Models\AdditionalPackage;
use App\Services\BaseService;

class AdditionalPackageService extends BaseService
{
    public $filterRules = [
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'merchant_id' => ['=', 'merchant_id'],
        'package_no'=>['like','package_no'],
    ];

    public function __construct(AdditionalPackage $model)
    {
        parent::__construct($model, AdditionalPackageResource::class);
    }

    public function getPageList()
    {
        $this->query->orderByDesc('created_at');
        return parent::getPageList();
    }
}
