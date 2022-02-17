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

class AuthRequest extends Request
{
    protected $scenes = [
        'login' => ['name', 'password'],
        'logout' => [],
        'test' => [],
        'registerCode' => ['name'],
        'register' => ['name', 'code', 'password', 'confirm_password'],
        'resetCode' => ['name'],
        'reset' => ['name', 'code', 'password', 'confirm_password'],
    ];

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'name' => 'required|max:20|email',
            'password' => 'required|max:20',
            'confirm_password' => 'required|string|same:password',
            'code' => 'required|string|digits:6',
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
        ];
    }
}
