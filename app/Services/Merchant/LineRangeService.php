<?php
/**
 * 线路范围 服务
 * User: long
 * Date: 2019/12/21
 * Time: 10:37
 */

namespace App\Services\Merchant;


use App\Models\LineRange;
use App\Services\Merchant\BaseService;

class LineRangeService extends BaseService
{
    public function __construct(LineRange $lineRange)
    {
        parent::__construct($lineRange);
    }
}
