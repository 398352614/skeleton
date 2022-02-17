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

use App\Model\User;
use App\Request\UserRequest;
use App\Resource\UserResource;
use Hyperf\Database\Model\Builder;

class UserService extends Service
{
    public function __construct(User $model, UserResource $resource, UserRequest $request)
    {
        parent::__construct(
            $model,
            $resource,
            $request
        );
    }

    public function store($data, bool $returnId = false): array|int|Builder
    {
        $data['password'] = hash('sha256', $data['password']);
        return parent::store($data, $returnId);
    }

}
