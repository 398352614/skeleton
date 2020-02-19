<?php

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;

trait HasLoginControl
{
    /**
     * 禁止登陆控制
     * @param  array  $id
     * @param  bool  $enabled
     * @return bool
     */
    public function forbidLogin(array $id, bool $enabled): bool
    {
        return $this->model::whereIn('id', $id)
                ->update([
                    'forbid_login' => $enabled
                ]) !== false;
    }
}
