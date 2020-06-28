<?php

namespace App\Http\Middleware;

use App\Exceptions\BusinessLogicException;
use App\Traits\CompanyTrait;
use Closure;
use Illuminate\Http\Request;

class ImportCheck
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws BusinessLogicException
     */
    public function handle($request, Closure $next)
    {
        if(CompanyTrait::getAddressTemplateId() == 2){
            throw new BusinessLogicException('地址模板二无法进行批量导入，请联系管理员');
        }
        return $next($request);
    }
}
