<?php
/**
 * 企业
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
     * 获取企业选择的国家
     * @return mixed|string
     */
    public static function getCountry()
    {
        $company = self::getCompany();
        return $company['country'] ?? '';
    }

    /**
     * 获取企业信息
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function getCompany()
    {
        $rootKey = config('tms.cache_prefix.company');
        $tag = config('tms.cache_tags.company');
        $companyId = auth()->user()->companyConfig->company_id;
        $company = Cache::tags($tag)->get($rootKey . $companyId);
        if (empty($company)) {
            Artisan::call('company:cache --company_id=' . $companyId);
            $company = Cache::tags($tag)->get($rootKey . $companyId);
        }
        return $company;
    }
}