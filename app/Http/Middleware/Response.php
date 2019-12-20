<?php
/**
 * 返回响应中间件
 * User: long
 * Date: 2019/7/24
 * Time: 11:10
 */

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as BaseResponse;

class Response
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        // 执行动作
        if ($response instanceof JsonResponse) {
            //若是异常抛出,则不处理数据,直接返回
            if (!empty($response->exception)) {
                return $response;
            }
            //若是非异常抛出,则进行数据处理
            $data = $response->getData();
            if (is_string($data) && isJson($data)) {
                $data = json_decode($data, JSON_UNESCAPED_UNICODE);
            }
            $response = $response->setData($this->responseFormat(200, $data));
        } elseif ($response instanceof BaseResponse) {
            //若是异常抛出,则不处理数据,直接返回
            if (!empty($response->exception)) {
                return $response;
            }
            $data = $response->getContent();
            if (is_string($data) && isJson($data)) {
                $data = json_decode($data, JSON_UNESCAPED_UNICODE);
            }
            $response->setContent($this->responseFormat(200, $data));
        }
        return $response;
    }
}
