<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Country;
use App\Models\MapConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class CacheCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:cache
                                            {--company_id= : company id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cache company list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            //获取公司ID
            $companyId = $this->option('company_id');
            //获取配置
            $rootKey = config('tms.cache_prefix.company');
            $tag = config('tms.cache_tags.company');
            //1.若只缓存一个企业
            if (!empty($companyId)) {
                $country = Country::query()->where('company_id', $companyId)->first(['short', 'en_name', 'cn_name']);
                $company = Company::query()->where('id', $companyId)->first();
                $companyConfig = !empty($company->companyConfig) ? Arr::only($company->companyConfig->getAttributes(), ['address_template_id', 'stock_exception_verify', 'line_rule', 'show_type', 'weight_unit', 'currency_unit', 'volume_unit', 'map']) : [];
                $mapConfig = MapConfig::query()->where('company_id', $companyId)->first();
                $company = array_merge(
                    Arr::only($company->getAttributes(), ['id', 'name', 'company_code']),
                    $companyConfig,
                    ['country' => $country['short'] ?? '', 'country_en_name' => $country['en_name'] ?? '', 'country_cn_name' => $country['cn_name'] ?? '']
                    //, ['map_config' => $mapConfig]
                );
                Cache::tags($tag)->forget($rootKey . $company['id']);
                Cache::tags($tag)->forever($rootKey . $company['id'], $company);
                $this->info('cache company successful');
                return;
            }
            //2.缓存所有企业
            $countryList = collect(Country::query()->get(['company_id', 'short', 'en_name', 'cn_name']))->unique('company_id')->keyBy('company_id')->toArray();
            $mapConfigList = collect(Country::query()->get())->unique('company_id')->keyBy('company_id')->toArray();
            $companyList = collect(Company::query()->get())->map(function ($company) use ($countryList,$mapConfigList) {
                /**@var \App\Models\Company $company */
                $companyConfig = !empty($company->companyConfig) ? Arr::only($company->companyConfig->getAttributes(), ['address_template_id', 'stock_exception_verify', 'line_rule', 'show_type', 'weight_unit', 'currency_unit', 'volume_unit', 'map']) : [];
                $company = $company->getAttributes();
                return collect(array_merge(
                    Arr::only($company, ['id', 'name', 'company_code']),
                    $companyConfig,
                    //['map_config'=>$mapConfigList[$company['id']]],
                    ['country' => $countryList[$company['id']]['short'] ?? '', 'country_en_name' => $countryList[$company['id']]['en_name'] ?? '', 'country_cn_name' => $countryList[$company['id']]['cn_name'] ?? '']
                ));
            })->toArray();
            foreach ($companyList as $company) {
                Cache::tags($tag)->forget($rootKey . $company['id']);
                Cache::tags($tag)->forever($rootKey . $company['id'], $company);
            }
            $this->info('cache company list successful');
            return;
        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
            return;
        }
    }
}
