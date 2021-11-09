<?php

namespace App\Console\Commands\Data;

use App\Models\Company;
use App\Models\CompanyCustomize;
use App\Services\BaseConstService;
use Illuminate\Console\Command;

class FixCompanyCustomize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:company-customize';

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
            if (empty(CompanyCustomize::query()->where('company_id', $company['id'])->first())) {
                CompanyCustomize::create([
                    'company_id' => $company['id'],
                    'status' => BaseConstService::YES,
                    'admin_url' => '',
                    'admin_login_background' => '',
                    'admin_login_title' => '',
                    'admin_main_logo' => '',
                    'merchant_url' => '',
                    'merchant_login_background' => '',
                    'merchant_login_title' => '',
                    'merchant_main_logo' => '',
                    'driver_login_title' => '',
                    'consumer_url' => '',
                    'consumer_login_title' => '',
                ]);
                $this->info($k);
            }
        }
        $this->info('fix company customize successful!');
    }
}
