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
namespace App\Service;

use App\Model\Route;
use App\Model\Station;

class RouteService extends Service
{
    public function __construct(Route $model, RouteRequest $request, RouteResource $resource)
    {
        parent::__construct(
            $model,
            $resource,
            $request
        );
    }

    public function sort(array $all)
    {
        make(StationService::class)->sort();
    }
}
