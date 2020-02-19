<?php
/**
 * @Author: h9471
 * @Created: 2020/1/4 11:16
 */

namespace App\Services\Merchant;


trait HasEnabledSet
{
    /**
     *
     * @param  int  $id
     * @param  bool  $enabled
     * @return bool
     */
    public function setEnabled(int $id, bool $enabled): bool
    {
        return $this->updateById($id, [
                'enabled' => $enabled
            ]) === false;
    }
}
