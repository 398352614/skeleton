<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/10/29
 * Time: 11:39
 */

namespace App\Services\Driver;


use App\Models\LineArea;
use App\Services\BaseService;

class LineAreaService extends BaseService
{
    public function __construct(LineArea $lineArea, $resource = null, $infoResource = null)
    {
        parent::__construct($lineArea, $resource, $infoResource);
    }
}