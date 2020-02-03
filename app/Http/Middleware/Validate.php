<?php

/**
 * 验证中间件
 * User: long
 * Date: 2019/7/24
 * Time: 15:49
 */

namespace App\Http\Middleware;

use App\Exceptions\BusinessLogicException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use App\Http\Validate\BaseValidate;

class Validate
{
    public static $baseNamespace = 'App\\Http\\Validate';

    /**@var BaseValidate $validate */
    protected $validate;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws BusinessLogicException
     */
    public function handle($request, $next)
    {
        $data = $request->all();
        $action = $request->route()->getAction();
        try {
            //替换命名空间
            $baseNamespace = str_replace('App\\Http\\Controllers', self::$baseNamespace, $action['namespace']);
            list($controller, $method) = explode('@', $action['controller']);
            //将控制器替换成验证类
            $controllerName = str_replace('Controller', 'Validate', substr($controller, (strrpos($controller, '\\') + 1)));
            //合成验证类
            $validateClass = $baseNamespace . '\\' . $controllerName;
            //若不存在验证规则和场景,则不验证
            if (!class_exists($validateClass) || !property_exists($validateClass, 'rules') || !property_exists($validateClass, 'scene')) {
                return $next($request);
            }
            /************************************验证规则获取 start****************************************************/
            //获取验证规则
            $this->validate = new $validateClass();
            //若验证规则或场景为空,也不验证
            if (empty($this->validate->rules) || empty($this->validate->scene[$method])) {
                return $next($request);
            }
            //若存在明细,则获取明细规则
            $item_rules = [];
            $sceneRules = $this->validate->scene[$method];
            if (!empty($sceneRules['item_list'])) {
                $item_rules = $this->getRules($this->validate->item_rules, $sceneRules['item_list']);
                unset($sceneRules['item_list']);
            }
            //获取主表规则
            $rules = $this->getRules($this->validate->rules, $sceneRules);
            /************************************验证规则获取 end******************************************************/
            /********************************************数据验证 start************************************************/
            //主表验证
            $this->validate($data, $rules, array_merge(BaseValidate::baseMessage(), $this->validate::message()), $this->validate::customAttributes());
            //明细验证
            if (!empty($item_rules) && !empty($data['item_list'])) {
                $itemList = json_decode($data['item_list'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new BusinessLogicException('明细数据格式不正确', 3001);
                }
                foreach ($itemList as $item) {
                    $this->validate($item, $item_rules, array_merge(BaseValidate::baseMessage(), $this->validate::item_message()), $this->validate::itemCustomAttributes());
                }
            }
            /*********************************************数据验证 end*************************************************/
        } catch (\Exception $ex) {
            throw new BusinessLogicException($ex->getMessage(), $ex->getCode());
        }

        return $next($request);
    }

    /**
     * 获取验证规则
     * @param $rules
     * @param $scene
     * @return array
     */
    public function getRules($rules, $scene)
    {
        return Arr::only($rules, $scene);
    }

    /**
     * 验证
     * @param $data
     * @param $rules
     * @param $message
     * @param $customAttributes
     * @throws BusinessLogicException
     */
    private function validate($data, $rules, $message, $customAttributes)
    {
        $validator = Validator::make($data, $rules, $message, $customAttributes);
        if ($validator->fails()) {
            $messageList = Arr::flatten($validator->errors()->getMessages());
            throw new BusinessLogicException(implode(';', $messageList), 3001);
        }
        request()->validated = $validator->validated(); // 控制器先被初始化,然后才进入的中间件!!!
    }
}
