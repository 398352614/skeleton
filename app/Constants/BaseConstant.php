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
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
#[Constants]
class BaseConstant extends AbstractConstants
{
    public const REGISTER_CODE = 'REGISTER';

    /**
     * @Message("是")
     */
    public const YES = 1;

    public const NO = 1;

    public const CONTENT_TYPE_JSON = 1;
    public const CONTENT_TYPE_FORM_DATA = 2;

    public const METHOD_GET = 1;
    public const METHOD_POST = 2;
}
