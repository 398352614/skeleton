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

namespace App\Resource;

use Hyperf\Resource\Json\JsonResource;

class Resource extends JsonResource
{
    public function __construct($resource = null)
    {
        return parent::__construct($resource);
    }

    public function toArray(): array
    {
        return parent::toArray();
    }
}
