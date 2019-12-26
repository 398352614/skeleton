<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/26
 * Time: 15:34
 */

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\Controller;
use App\Services\CommonService;

/**
 * Class CommonController
 * @package App\Http\Controllers\Api\Admin
 * @property CommonService $service
 */
class CommonController extends Controller
{
    public function __construct(CommonService $service)
    {
        $this->service = $service;
    }
}