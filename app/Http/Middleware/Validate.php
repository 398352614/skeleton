<?php

/**
 * 验证中间件
 * User: long
 * Date: 2019/7/24
 * Time: 15:49
 */

namespace App\Http\Middleware;

use App\Exceptions\BusinessLogicException;
use App\Http\Validate\BaseValidate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            //获取验证规则
            $rules = $this->getRules($this->validate->rules, $this->validate->scene[$method], $method);
            /************************************验证规则获取 end******************************************************/
            /********************************************数据验证 start************************************************/
            //验证
            $this->validate($data, $rules, array_merge(BaseValidate::$baseMessage, $this->validate->message), $this->validate->customAttributes ?? [], $request);
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
     * @param $method
     * @return array
     */
    public function getRules($rules, $scene, $method)
    {
        $rules = Arr::only($rules, $scene);
        //获取地址验证规则
//        if (in_array($method, ['store', 'update'])) {
//            $validateName = get_class($this->validate);
//            $type = strtolower(str_replace('Validate', '', substr($validateName, (strrpos($validateName, '\\') + 1))));
//            if (in_array($type, ['order', 'place', 'second_place', 'warehouse'])) {
//                $addressRules = AddressTemplateTrait::getFormatAddressTemplate($type);
//                $rules = array_merge($rules, $addressRules);
//            }
//        }
        return $rules;
    }

    /**
     * 验证
     * @param $data
     * @param $rules
     * @param $message
     * @param $customAttributes
     * @param \Illuminate\Http\Request $request
     * @throws BusinessLogicException
     */
    private function validate($data, $rules, $message, $customAttributes, $request)
    {
        //处理json数组
        foreach ($data as $key => $value) {
            if (is_string($value) && Str::contains($key, '_list') && !Str::contains($key, 'id_list') && isJson($value)) {
                $value = json_decode($value, true);
                $request->offsetSet($key, $value);
                $data[$key] = $value;
            }
        }
        //规则验证
        $validator = Validator::make($data, $rules, $message, $customAttributes);
        if ($validator->fails()) {
            $messageList = Arr::flatten($validator->errors()->getMessages());
            throw new BusinessLogicException(implode(';', $messageList), 3001);
        }
        request()->validated = $validator->validated(); // 控制器先被初始化,然后才进入的中间件!!!
    }
}
