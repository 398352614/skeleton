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
    ];

    public $customAttributes = [];

    public $rules = [];

    public $scene = [];

    public $message = [];

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
        $model = $query->where($attribute, '=', $value)->first();
        return empty($model) ? true : false;
    }
}