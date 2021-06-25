<?php
/**
 * 业务异常
 * User: long
 * Date: 2019/7/24
 * Time: 12:22
 */

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Cache;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;


/**
 * Class BusinessLogicException
 * @package App\Exceptions
 */
class BusinessLogicException extends Exception
{
    use ResponseTrait;

    public $replace = [];

    public $data = '';

    /**
     * BusinessLogicException constructor.
     * @param  string  $message
     * @param  int  $code
     * @param  array  $replace
     * @param  string  $data
     * @param  Throwable|null  $previous
     */
    public function __construct($message = "", $code = 1000, $replace = [], $data = '', Throwable $previous = null)
    {
        $this->replace = $replace;
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    /**
     *
     */
    public function report()
    {
        Log::channel('info')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'BusinessLogicException',['message' => $this->message, 'code' => $this->code]);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json($this->responseFormat($this->getCode(), $this->data, $this->getMessage(), $this->replace));
    }

    /**
     * @param  string  $msg
     * @param  int  $code
     * @return int|mixed
     */
    public function getErrorCode(string $msg, int $code)
    {
        $tag = config('tms.cache_tags.error_code');

        $errorCodeData = Cache::get($tag);

        if (empty($errorCodeData)) {
            $errorCodeFile = file_get_contents(config('tms.error_code_path'));
            $errorCodeData = json_decode($errorCodeFile, true);

            Cache::put($tag, $errorCodeData, 86400);
        }

        if (array_key_exists($msg, $errorCodeData)) {
            return $errorCodeData[$msg];
        } else {
            return $code;
        }
    }
}
