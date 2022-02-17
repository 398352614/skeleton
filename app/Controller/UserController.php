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
namespace App\Controller;

use App\Service\UserService;


/**
 * Class UserController.
 * @property UserService $service
 */
class UserController extends Controller
{
    protected array $data;

    /**
     * UserController constructor.
     */
    public function __construct(UserService $service)
    {
        parent::__construct($service);
    }


}
