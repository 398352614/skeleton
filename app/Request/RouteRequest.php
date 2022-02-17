<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Request;

class RouteRequest extends Request
{
    protected $scenes = [
        'store' => ['name', 'password'],
        'show' => [],
        'index' => [],
        'destroy' => [],
        'edit' => ['name', 'password'],
    ];

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'name' => 'required|max:20|unique:user,name,id,id',
            'password' => 'required|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            ...parent::messages(),
            'required' => ':attribute 是必须的',
            'key.required' => '娃哈哈',
        ];
    }

    public function attributes(): array
    {
        return [
            ...parent::attributes(),
            'name' => '姓名',
            'password' => '密码',
        ];
    }
}
