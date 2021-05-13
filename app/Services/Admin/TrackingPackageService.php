<?php


namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\BagResource;
use App\Models\Bag;
use App\Models\TrackingPackage;
use App\Services\Admin\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TrackingPackageService extends BaseService
{
    public $filterRules = [
        'status' => ['=', 'status'],
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(TrackingPackage $model)
    {
        parent::__construct($model);
    }

}
