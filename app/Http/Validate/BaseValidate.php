<?php
/**
 * BaseValidate
 * User: long
 * Date: 2019/7/24
 * Time: 17:09
 */

namespace App\Http\Validate;

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
        '*.required' => ':attribute字段必填',
        '*.string' => ':attribute字段必须是字符串',
        '*.min' => ':attribute字段不能小于:min个字符',
        '*.max' => ':attribute字段不能超过:max个字符',
        '*.unique_ignore' => ':attribute已存在',
        '*.between' => ':attribute范围必须在:min-:max之间',
        '*.after_or_equal' => ':attribute必须在当前日期及之后',
        '*.array' => ':attribute必须是数组',
        'code.digits_between'=>'验证码必须为6位数',
        '*.digits_between'=>':attribute必须在:min位-:max位之间'
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
        if (in_array('company_id', $parameters)) {
            $query->where('company_id', '=', auth()->user()->company_id);
        }
        $model = $query->where($attribute, '=', $value)->first();
        return empty($model) ? true : false;
    }
}
