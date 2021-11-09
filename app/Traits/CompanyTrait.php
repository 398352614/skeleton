<?php
/**
 * 公司
 * User: long
 * Date: 2020/5/20
 * Time: 14:42
 */

namespace App\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

trait CompanyTrait
{
    /**
     * 获取公司选择的国家
     * @return mixed|string
     */
    public static function getCountry()
    {
        $company = self::getCompany();
        return $company['country'] ?? '';
    }

    /**
     * 获取公司信息
     * @param $companyId
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function getCompany($companyId = null)
    {
        $rootKey = config('tms.cache_prefix.company');
        $tag = config('tms.cache_tags.company');
        $companyId = $companyId ?? auth()->user()->company_id;
        $company = Cache::tags($tag)->get($rootKey . $companyId);
        if (empty($company)) {
            Artisan::call('cache:company --company_id=' . $companyId);
            $company = Cache::tags($tag)->get($rootKey . $companyId);
        }
        return $company;
    }

    /**
     *获取线路规则
     * @return mixed|string
     */
    public static function getLineRule()
    {
        $company = self::getCompany();
        return $company['line_rule'] ?? '';
    }

    /**
     * 获取地址模板ID
     * @return mixed|string
     */
    public static function getAddressTemplateId()
    {
        $company = self::getCompany();
        return $company['address_template_id'] ?? '';
    }
}
