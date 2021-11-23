<?php

namespace App\Console\Commands\Data;

use App\Http\Controllers\Api\Merchant\Api\Merchant\Api\Admin\RegisterController;
use App\Models\Company;
use App\Models\Ledger;
use App\Models\Merchant;
use App\Services\BaseConstService;
use App\Traits\FactoryInstanceTrait;
use Illuminate\Console\Command;

class FixLedger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:ledger';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix ledger table';

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
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function handle()
    {
        $companyList = Company::query()->get(['*'])->toArray();
        foreach ($companyList as $k => $company) {
            $merchantList = Merchant::query()->where('company_id', $company['id'])->get();
            foreach ($merchantList as $merchant) {
                if (empty(Ledger::query()->where('user_id', $merchant['id'])->where('user_type', BaseConstService::USER_MERCHANT)->first())) {
                    $class = FactoryInstanceTrait::getInstance(RegisterController::class);
                    $class->addLedgerOfMerchant($company, $merchant);
                }
            }
        }
        $this->info('Stock fix successful!');
    }
}
