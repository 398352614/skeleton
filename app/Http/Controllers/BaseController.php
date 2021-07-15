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

    /**
     * BaseController constructor.
     * @param  BaseService  $service
     * @param  array  $exceptMethods
     */
    public function __construct(BaseService $service, $exceptMethods = [])
    {
        $this->service = new TransactionService($service, $exceptMethods);
        $this->data = Request::all();
    }

    /**
     * Execute an action on the controller.
     *
     * @param string $method
     * @param array $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        $this->beforeAction($parameters);
        return call_user_func_array([$this, $method], $parameters);
    }

    /**
     * @param $parameters
     */
    public function beforeAction($parameters)
    {
        $this->data = Request::all();
    }

    /**
     * 手动设置data值
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
