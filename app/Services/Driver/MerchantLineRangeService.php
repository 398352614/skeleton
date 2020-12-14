<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/10/29
 * Time: 11:36
 */

namespace App\Services\Driver;

use App\Models\MerchantLineRange;
use App\Services\BaseService;

class MerchantLineRangeService extends BaseService
{
    public function __construct(MerchantLineRange $merchantLineRange, $resource = null, $infoResource = null)
    {
        parent::__construct($merchantLineRange, $resource, $infoResource);
    }
}