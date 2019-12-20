<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 17:03
 */

namespace App\Http\Controllers;

use App\Services\BaseService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Request;

/**
 * Class BaseController
 * @package App\Http\Controllers
 * @property BaseService $service
 */
class BaseController extends Controller
{
    protected $service;

    protected $data;

    public function __construct(BaseService $service)
    {
        $this->service = new TransactionService($service);
        $this->data = Request::all();
    }
}