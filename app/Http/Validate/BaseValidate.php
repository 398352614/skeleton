<?php

/**
 * BaseValidate
 * User: long
 * Date: 2019/7/24
 * Time: 17:09
 */

namespace App\Http\Validate;

use App\Traits\CompanyTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
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

    /**
     * 验证id列表值是否是合法的
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function checkIdList($attribute, $value, $parameters, $validator)
    {
        $maxCount = $parameters[0] ?? 100;
        if(is_string($value)){
            $list = explode(',', $value);
            if (count($list) > $maxCount) return false;
            $id = Arr::first($list, function ($v) {
                return !is_numeric($v);
            });
        }
        return empty($id) ? true : false;
    }

    /**
     * 地址验证 是否必填
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function checkAddress($attribute, $value, $parameters, $validator)
    {
        if (empty($value) && CompanyTrait::getAddressTemplateId() == 2) {
            return false;
        }
        return true;
    }

    /**
     * 验证字段是否包含特殊字符
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function checkSpecialChar($attribute, $value, $parameters, $validator)
    {
        if (have_special_char($value)) return false;
        return true;
    }


}
