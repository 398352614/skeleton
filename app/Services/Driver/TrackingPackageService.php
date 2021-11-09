<?php


namespace App\Services\Driver;


use App\Http\Resources\Api\Driver\TrackingPackageResource;
use App\Models\TrackingPackage;
use App\Services\Admin\BaseService;

class TrackingPackageService extends BaseService
{
    public $filterRules = [
        'status' => ['=', 'status'],
        'type' => ['=', 'type']
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(TrackingPackage $model)
    {
        parent::__construct($model, TrackingPackageResource::class);
    }

    public function getPageList()
    {
        return parent::getPageList();
    }
}
