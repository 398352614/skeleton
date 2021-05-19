<?php


namespace App\Services\Driver;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\BagResource;
use App\Http\Resources\Api\Driver\TrackingPackageResource;
use App\Models\Bag;
use App\Models\TrackingPackage;
use App\Services\Admin\BaseService;
use App\Services\BaseConstService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
