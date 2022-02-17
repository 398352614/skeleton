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

use App\Middleware\AuthMiddleware;
use App\Middleware\ResponseMiddleware;
use App\Middleware\ValidateMiddleware;

return [
    'http' => [
        AuthMiddleware::class,
        ValidateMiddleware::class,
        ResponseMiddleware::class,
    ],
];
