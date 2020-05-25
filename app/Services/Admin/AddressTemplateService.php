<?php
/**
 * 地址模板 服务
 * User: long
 * Date: 2020/5/22
 * Time: 11:47
 */

namespace App\Services\Admin;

use App\Models\AddressTemplate;
use App\Services\BaseService;

class AddressTemplateService extends BaseService
{
    public function __construct(AddressTemplate $model)
    {
        parent::__construct($model);
    }
}