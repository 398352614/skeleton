<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 17:03
 */

namespace App\Http\Controllers\Api\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\BaseService;

/**
 * Class BaseController
 * @package App\Http\Controllers
 * @property BaseService $service
 */
class OrderBaseController extends BaseController
{
    protected $service;

    protected $data;

    public function __construct(BaseService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }


    /**
     * Execute an action on the controller.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     * @throws BusinessLogicException
     * @throws \ReflectionException
     */
    public function callAction($method, $parameters)
    {
        $this->beforeAction($parameters);
        $parameters = $this->dealParameters($method, $parameters);
        return call_user_func_array([$this, $method], $parameters);
    }

    /**
     * 处理参数
     *
     * @param $method
     * @param $parameters
     * @return array
     * @throws \ReflectionException
     * @throws BusinessLogicException
     */
    public function dealParameters($method, $parameters)
    {
        if (auth()->user()->getAttribute('is_api')) {
            $methodParameters = (new \ReflectionMethod($this, $method))->getParameters();
            if (!empty($methodParameters) && $methodParameters[0]->getName() === 'id') {
                if (empty($this->data['order_no'])) {
                    throw new BusinessLogicException('订单号是必须的');
                }
                $parameters['id'] = $this->data['order_no'];
                unset($this->data['order_no']);
            }
        }
        return $parameters;
    }
}
