<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/10/29
 * Time: 11:36
 */

namespace App\Services\Driver;


use App\Models\LineRange;
use App\Services\BaseService;

class LineRangeService extends BaseService
{
    public function __construct(LineRange $lineRange, $resource = null, $infoResource = null)
    {
        parent::__construct($lineRange, $resource, $infoResource);
    }
}