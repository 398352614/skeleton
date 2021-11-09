<?php

namespace App\Console\Commands\Data;

use App\Models\Company;
use App\Models\CompanyConfig;
use App\Models\PayConfig;
use App\Services\BaseConstService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPayConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:pay-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix pay config';

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
        $companyList = Company::query()->get(['*'])->toArray();
        foreach ($companyList as $k => $company) {
            if (empty(PayConfig::query()->where('company_id', $company['id'])->first())) {
                PayConfig::query()->create([
                    'company_id' => $company['id'],
                ]);
                $this->info($k);
            }
        }
        $this->info('fix company config successful!');
    }
}
