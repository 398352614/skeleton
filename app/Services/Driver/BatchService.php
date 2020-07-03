<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/30
 * Time: 10:57
 */

namespace App\Services\Driver;


use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Services\BaseConstService;
use App\Services\BaseService;

class BatchService extends BaseService
{
    public function __construct(Batch $batch)
    {
        parent::__construct($batch);
    }
}
