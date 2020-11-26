<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/4/7
 * Time: 17:37
 */

namespace App\Services\Admin;

use App\Services\BaseConstService;
use GatewayWorker\Lib\Gateway;

class WorkerService
{
    public function bind($clientId)
    {
        Gateway::bindUid($clientId, auth()->id());
        Gateway::joinGroup($clientId, BaseConstService::WORKER_GROUP_ADMIN);
        return $clientId;
    }
}