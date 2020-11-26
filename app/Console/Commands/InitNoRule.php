<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Fee;
use App\Models\OrderNoRule;
use App\Services\BaseConstService;
use Illuminate\Console\Command;

class InitNoRule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'no-rule:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'number rule init';

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
        $companyList = Company::query()->get(['id', 'company_code'])->toArray();
        if (!empty($companyList)) {
            foreach ($companyList as $company) {
                $recharge = OrderNoRule::query()->where('company_id', $company['id'])->where('type', BaseConstService::RECHARGE_NO_TYPE)->first();
                if (empty($recharge)) {
                    $prefix = BaseConstService::RECHARGE . $company['company_code'];
                    OrderNoRule::create([
                        'company_id' => $company['id'],
                        'type' => BaseConstService::RECHARGE_NO_TYPE,
                        'prefix' => $prefix,
                        'start_index' => 1,
                        'int_length' => 7,
                        'max_no' => $prefix . str_repeat('9', 7)
                    ]);
                }

                $trackingOrder = OrderNoRule::query()->where('company_id', $company['id'])->where('type', BaseConstService::TRACKING_ORDER_NO_TYPE)->first();
                if (empty($trackingOrder)) {
                    $prefix = BaseConstService::TRACKING_ORDER . $company['company_code'];
                    OrderNoRule::create([
                        'company_id' => $company['id'],
                        'type' => BaseConstService::TRACKING_ORDER_NO_TYPE,
                        'prefix' => $prefix,
                        'start_index' => 1,
                        'int_length' => 7,
                        'max_no' => $prefix . str_repeat('9', 7)
                    ]);
                }
            }
            $this->info('number rule init successful!');
        } else {
            $this->info('number rule init failed!');
        }
    }
}
