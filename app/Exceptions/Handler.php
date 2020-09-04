<?php

namespace App\Exceptions;

use App\Services\MessageService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        ModelNotFoundException::class,
        AuthenticationException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        if (($exception->getCode() == 0) && $this->shouldReport($exception)) {
            //企业微信报错
            $body = $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine();
            (new MessageService())->reportToWechat($body);
            app('log')->info('报错时参数' . json_encode(request()->input()));
        }
        parent::report($exception);
    }


    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json($this->responseFormat(1000, '', '数据不存在'));
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json($this->responseFormat(10401, '', '用户认证失败'));
        }

        return parent::render($request, $exception);
    }
}
