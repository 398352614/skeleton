<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/4/7
 * Time: 17:35
 */

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\WorkerService;

/**
 * Class WorkerController
 * @package App\Http\Controllers\Api\Admin
 * @property WorkerService $service
 */
class WorkerController
{
    protected $service;

    public function __construct(WorkerService $service)
    {
        $this->service = $service;
    }

    /**
     * 绑定
     *
     * @param $clientId
     * @return mixed
     */
    public function bind($clientId)
    {
        return $this->service->bind($clientId);
    }
}