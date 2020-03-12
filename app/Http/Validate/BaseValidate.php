<?php

/**
 * BaseValidate
 * User: long
 * Date: 2019/7/24
 * Time: 17:09
 */

namespace App\Http\Validate;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;
use tests\Mockery\Adapter\Phpunit\EmptyTestCase;

/**
 * Class BaseValidate
 * @package App\Http\Validate
 */
class BaseValidate
{
    public static $baseMessage = [

    ];

    public $customAttributes = [];

    public $itemCustomAttributes = [];

    public $rules = [];

    public $item_rules = [];

    public $scene = [];

    public $message = [];

    public $item_message = [];

    /**
     * 唯一验证
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param Validator $validator
     * @return bool
     */
    public function uniqueIgnore($attribute, $value, $parameters, $validator)
    {
        $table = $parameters[0];
        $primaryKey = $parameters[1];
        $query = DB::table($table);
        if ($id = request()->route($primaryKey)) {
            $query->where($primaryKey, '<>', $id);
        }
        if ($companyIdKey = array_search('company_id', $parameters)) {
            unset($parameters[$companyIdKey]);
            $query->where('company_id', '=', auth()->user()->company_id);
        }
        //若还有其他字段验证,则增加查询条件
        unset($parameters[0], $parameters[1]);
        if (!empty($parameters)) {
            $params = $validator->attributes();
            foreach ($parameters as $parameter) {
                !empty($params[$parameter]) && $query->where($parameter, '=', $params[$parameter]);
            }
        }
        $model = $query->where($attribute, '=', $value)->first();
        return empty($model) ? true : false;
    }

    public static function __callStatic($name, $arguments)
    {
        $arr = [];
        if (isset(self::$$name)) {
            $arr = self::$$name;
            foreach ($arr as $key => $value) {
                $msg = (strpos(__('msg.' . $value), 'msg.') === false) ? __('msg.' . $value) : $value;
                $arr[$key] = $msg; // 翻译
            }
        }
        return $arr;
    }
}
