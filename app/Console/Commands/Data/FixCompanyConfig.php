<?php

namespace App\Console\Commands\Data;

use App\Models\Company;
use App\Models\CompanyConfig;
use App\Services\BaseConstService;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixCompanyConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:company-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     */
    public function handle()
    {
        $companyList = Company::query()->get(['*'])->toArray();
        foreach ($companyList as $k => $company) {
            if (empty(CompanyConfig::query()->where('company_id', $company['id'])->get())) {
                CompanyConfig::create([
                    'company_id' => $company['id'],
                    'line_rule' => BaseConstService::LINE_RULE_POST_CODE,
                    'show_type' => BaseConstService::ALL_SHOW,
                    'address_template_id' => BaseConstService::ADDRESS_TYPE_1,
                    'stock_exception_verify' => BaseConstService::NO,
                    'weight_unit' => BaseConstService::WEIGHT_UNIT_TYPE_2,
                    'currency_unit' => BaseConstService::CURRENCY_UNIT_TYPE_3,
                    'volume_unit' => BaseConstService::VOLUME_UNIT_TYPE_2,
                    'map' => 'google',
                    'scheduling_rule' => BaseConstService::SCHEDULING_TYPE_1
                ]);
                $this->info($k);
            }
        }
        $this->info('fix company config successful!');
    }
}
