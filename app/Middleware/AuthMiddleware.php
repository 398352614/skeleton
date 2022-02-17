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
namespace App\Middleware;

use App\Exception\BusinessException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qbhy\HyperfAuth\AuthManager;

class AuthMiddleware implements MiddlewareInterface
{
    public array $except = [
        'App\Controller\AuthController@login',
        'App\Controller\AuthController@logout',
        'App\Controller\AuthController@registerCode',
        'App\Controller\AuthController@register',
        'App\Controller\AuthController@reset',
        'App\Controller\AuthController@resetCode',
        'App\Controller\AuthController@text',
    ];

    /**
     * @Inject
     */
    protected AuthManager $auth;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->authenticate();
        return $handler->handle($request);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function authenticate()
    {
        $controller = $this->request->getAttribute(Dispatched::class)->handler->callback;
        if (in_array($controller, $this->except)) {
            return;
        }
        if (! $this->auth->guard('jwt')->check()) {
            throw new BusinessException('验证失败');
        }
    }
}
