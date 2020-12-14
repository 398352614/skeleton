<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/7
 * Time: 10:50
 */

namespace App\Services\Merchant;

use App\Models\LineArea;

class LineAreaService extends BaseService
{
    public function __construct(LineArea $lineArea, $resource = null, $infoResource = null)
    {
        parent::__construct($lineArea, $resource, $infoResource);
    }
}
