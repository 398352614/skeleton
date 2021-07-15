<?php
/**
 * 事物服务
 * Date: 2019-12-16
 * Time: 16:21
 */

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use Illuminate\Support\Facades\DB;

/**
 * Class TransactionManager
 * @package app\common\util
 * @property BaseService $service
 */
class TransactionService
{
    protected $service;

    protected $exceptMethods = [];

    public function __construct(BaseService $service, $exceptMethods = [])
    {
        $this->service = $service;
        $this->exceptMethods = $exceptMethods;
    }


    /**
     * 魔术方法，根据方法名调用logic，对应的方法（加上事物管理）
     * @param $method
     * @param $arguments
     * @return mixed|null
     * @throws \Exception
     */
    function __call($method, $arguments)
    {
        if (in_array($method, $this->exceptMethods)) {
            return call_user_func_array([$this->service, $method], !empty($arguments) ? $arguments : []);
        }
        // show/get/query/find 表示只读事物
        $pattern = '/^(show\w*)$|^(get\w*)$|^(select\w*)$|^(query\w*)$|^(find\w*)$|^(export\w*)|^(index\w*)$/';
        preg_match($pattern, $method, $match);
        // 匹配上了，就直接执行只读方法
        if (is_array($match) && count($match)) {
            $return = call_user_func_array([$this->service, $method], !empty($arguments) ? $arguments : []);
        } else {
            // 没有匹配上，则加上事物执行
            $return = $this->transaction($method, !empty($arguments) ? $arguments : []);
        }
        return $return;
    }

    /**
     * 事务操作
     * @param $method
     * @param array $param
     * @return mixed|null
     * @throws \Exception
     */
    public function transaction($method, array $param)
    {
        try {
            // 开启事物
            DB::beginTransaction();
            if (!method_exists($this->service, $method)) {
                throw new BusinessLogicException($method . '方法未定义', 8001);
            }
            $return = call_user_func_array([$this->service, $method], $param);
            // 提交事物
            DB::commit();
        } catch (BusinessLogicException $e) {
            // 回滚事物
            DB::rollBack();
            throw new BusinessLogicException($e->getMessage(), $e->getCode(), $e->replace, $e->data);
        } catch (\Exception $e) {
            // 回滚事物
            DB::rollBack();
            throw $e;
        }
        return $return;
    }
}
