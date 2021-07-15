<?php


namespace App\Http\Middleware;


use App\Exceptions\BusinessLogicException;
use App\Models\MapConfig;
use App\Traits\CompanyTrait;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class companyValidate extends Middleware
{
    use CompanyTrait;

    /**
     * @param Request $request
     * @param Closure $next
     * @param array $guards
     * @return mixed
     * @throws BusinessLogicException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ($guards[0] === 'admin') {
            $info = str_replace('App\\Http\\Controllers\\Api\\Admin\\', '', $request->route()->getActionName());
            $this->companyValidate($info, $this->adminExcept);
        } elseif ($guards[0] === 'merchant') {
            $info = str_replace('App\\Http\\Controllers\\Api\\Merchant\\', '', $request->route()->getActionName());
            $this->companyValidate($info, $this->merchantExcept);
        } elseif ($guards[0] === 'driver') {
            $info = str_replace('App\\Http\\Controllers\\Api\\Driver\\', '', $request->route()->getActionName());
            $this->companyValidate($info, $this->driverExcept);
        }
        return $next($request);
    }

    /**
     * 验证公司配置
     * @param $info
     * @param $except
     * @throws BusinessLogicException
     */
    private function companyValidate($info, $except)
    {
        if (!in_array($info, $except)) {
            $arr = [
                'line_rule',
                'address_template_id',
                'country'
            ];
//            if (!Arr::has(self::getCompany(auth()->user()->company_id), $arr)) {
//                throw new BusinessLogicException('请先联系管理员到配置管理，填写高级配置内容');
//            }
//            $mapConfig = MapConfig::query()->where('company_id', auth()->user()->company_id)->first();
//            if (empty($mapConfig) ||
//                (
//                    empty($mapConfig->toArray()['google_key']) &&
//                    empty($mapConfig->toArray()['tencent_key']) &&
//                    empty($mapConfig->toArray()['baidu_key'])
//                )
//            ) {
//                throw new BusinessLogicException('请先联系管理员到配置管理，填写高级配置内容');
//            }
        }
    }

    protected $adminExcept = [
        'AuthController@me',
        'AuthController@logout',
        'AuthController@updatePassword',
        'CompanyController@index',
        'CompanyController@update',
        'CompanyConfigController@show',
        'CompanyConfigController@getAddressTemplateList',
        'CompanyConfigController@update',
        'CountryController@index',
        'CountryController@initStore',
        'CountryController@store',
        'CountryController@destroy',
        'CommonController@getCountryList',
        'AuthController@getPermission',
        'CommonController@dictionary',
        'HomeController@home',
        'HomeController@thisWeekCount',
        'HomeController@lastWeekCount',
        'HomeController@thisMonthCount',
        'HomeController@lastMonthCount',
        'HomeController@periodCount',
        'HomeController@merchantCount',
        'HomeController@merchantTotalCount'
    ];

    protected $merchantExcept = [
        'AuthController@me',
        'AuthController@logout',
        'AuthController@updatePassword',
        'MerchantController@update',
        'MerchantApiController@update',
        'MerchantApiController@show',
        'MerchantApiController@update',
        'CommonController@getCountryList',
        'CommonController@getLocation'
    ];


    protected $driverExcept = [
        'AuthController@logout',
        'AuthController@me',
        'AuthController@updatePassword',
        'AuthController@refresh',
    ];
}
