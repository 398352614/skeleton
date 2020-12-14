<?php

namespace App\Traits;

trait HasLoginControlTrait
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
