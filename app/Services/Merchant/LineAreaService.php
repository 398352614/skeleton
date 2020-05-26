<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/7
 * Time: 10:50
 */

namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Models\LineArea;
use App\Services\BaseService;
use App\Traits\ConstTranslateTrait;
use App\Traits\MapAreaTrait;
use Illuminate\Database\Eloquent\Model;

class LineAreaService extends BaseService
{
    public function __construct(LineArea $lineArea, $resource = null, $infoResource = null)
    {
        parent::__construct($lineArea, $resource, $infoResource);
    }
}