<?php


namespace App\Http\Middleware;


use App\Exceptions\BusinessLogicException;
use App\Traits\CompanyTrait;
use Closure;
use Illuminate\Http\Request;

class companyValidate
{
    use CompanyTrait;

    /**
     * @param Request $request
     * @param Closure $next
     * @param array $guards
     * @return mixed
     * @throws BusinessLogicException
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $info=str_replace('App\\Http\\Controllers\\Api\\Admin\\','',$request->route()->getActionName());
        if(!in_array($info,$this->except)){
            if(empty(self::getCompany(auth()->user()->company_id)['country'])){
                throw new BusinessLogicException('无法进行该操作，请先进行基础配置');
            }
        }
        return $next($request);
    }

    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        'AuthController@me',
        'AuthController@logout',
        'AuthController@updatePassword',
        'CompanyController@index',
        'CompanyController@update',
        'CompanyConfigController@show',
        'CompanyConfigController@getAddressTemplateList',
        'CompanyConfigController@update',
    ];
}
