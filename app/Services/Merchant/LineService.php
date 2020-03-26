<?php
/**
 * 线路 服务
 * User: long
 * Date: 2019/12/21
 * Time: 10:05
 */

namespace App\Services\Merchant;

use App\Models\Line;
use App\Services\BaseService;

class LineService extends BaseService
{
    public function __construct(Line $line)
    {
        parent::__construct($line);
    }
}
