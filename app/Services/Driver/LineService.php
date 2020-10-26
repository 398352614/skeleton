<?php
/**
 * 线路 服务
 * User: long
 * Date: 2019/12/21
 * Time: 10:05
 */

namespace App\Services\Driver;


use App\Models\Line;


class LineService extends BaseService
{
    public function __construct(Line $line)
    {
        parent::__construct($line);
    }

    /**
     * 获取线路列表
     * @return mixed
     */
    public function getPageList()
    {
        return parent::getPageList();
    }
}
