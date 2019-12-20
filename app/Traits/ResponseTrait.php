<?php
/**
 * 返回响应trait
 * User: long
 * Date: 2019/7/24
 * Time: 12:08
 */

namespace App\Traits;

trait ResponseTrait
{
    /**
     * @param int $code
     * @param mixed $data
     * @param string $msg
     * @return array
     */
    public function responseFormat($code = 200, $data = null, $msg = 'successful')
    {
        return [
            'code' => $code,
            'data' => $data,
            'msg' => __($msg)
        ];
    }

    public function jsonResponse($code = 200, $data = null, $msg = 'successful')
    {
        if (is_string($data) && isJson($data)) {
            $data = json_decode($data, JSON_UNESCAPED_UNICODE);
        }
        return response()->json($this->responseFormat($code, $data, $msg));
    }
}