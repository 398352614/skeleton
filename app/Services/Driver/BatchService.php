<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/30
 * Time: 10:57
 */

namespace App\Services\Driver;


use App\Models\Batch;
use App\Services\BaseService;

class BatchService extends BaseService
{
    public function __construct(Batch $batch)
    {
        $this->request = request();
        $this->model = $batch;
        $this->query = $this->model::query();
    }


}