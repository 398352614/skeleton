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

use App\Service\RouteService;
use App\Service\UserService;

/**
 * Class RouteService.
 * @property RouteService $service
 */
class RouteController extends Controller
{

    /**
     * UserController constructor.
     */
    public function __construct(RouteService $service)
    {
        parent::__construct($service);
    }

    public function sort()
    {
        $this->service->sort($this->request->all());
    }
}
