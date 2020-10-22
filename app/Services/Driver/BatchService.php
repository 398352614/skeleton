<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/30
 * Time: 10:57
 */

namespace App\Services\Driver;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\BatchInfoResource;
use App\Http\Resources\Api\Driver\BatchResource;
use App\Models\Batch;
use App\Services\BaseConstService;


class BatchService extends BaseService
{
    public function __construct(Batch $batch)
    {
        parent::__construct($batch, BatchResource::class, BatchInfoResource::class);
    }
}
