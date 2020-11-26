<?php
/**
 * 异常管理 服务
 * User: long
 * Date: 2020/1/3
 * Time: 16:26
 */

namespace App\Services\Merchant;

use App\Models\BatchException;

class BatchExceptionService extends BaseService
{
    public function __construct(BatchException $batchException)
    {
        parent::__construct($batchException);

    }
}
