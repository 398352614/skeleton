<?php
/**
 * 业务异常
 * User: long
 * Date: 2019/7/24
 * Time: 12:22
 */

namespace App\Exceptions;

use App\Traits\ResponseTrait;
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

    public function __construct($message = "", $code = 1000, $replace = [], $data = '', Throwable $previous = null)
    {
        $this->replace = $replace;
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }


    /**
     * @param Exception $exception
     * @throws Exception
     */
    public function report()
    {
        Log::error('错误', ['message' => $this->message, 'code' => $this->code]);
    }


    public function render($request)
    {
        return response()->json($this->responseFormat($this->getCode(), $this->data, $this->getMessage(), $this->replace));
    }
}