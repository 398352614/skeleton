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

use App\Service\Service;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Resource\Json\JsonResource;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    /**
     * @Inject
     */
    protected ContainerInterface $container;

    /**
     * @Inject
     */
    protected RequestInterface $request;

    /**
     * @Inject
     */
    protected ResponseInterface $response;

    protected Service $service;

    protected JsonResource $resource;


}
