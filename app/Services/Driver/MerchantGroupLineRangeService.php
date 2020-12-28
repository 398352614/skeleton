<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/10/29
 * Time: 11:36
 */

namespace App\Services\Driver;

use App\Models\MerchantGroupLineRange;
use App\Services\BaseService;

class MerchantGroupLineRangeService extends BaseService
{
    public function __construct(MerchantGroupLineRange $merchantGroupLineRange, $resource = null, $infoResource = null)
    {
        parent::__construct($merchantGroupLineRange, $resource, $infoResource);
    }
}