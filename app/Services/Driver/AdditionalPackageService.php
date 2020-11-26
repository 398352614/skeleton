<?php

namespace App\Services\Driver;

use App\Http\Resources\Api\Driver\AdditionalPackageResource;
use App\Models\AdditionalPackage;

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
}
