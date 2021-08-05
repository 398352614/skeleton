<?php

namespace App\Console\Commands\Data;

use App\Models\Company;
use App\Models\OrderNoRule;
use App\Services\BaseConstService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Console\Command;

class InitNoRule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:no-rule';

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
        if (is_null($companyList)) {
            $this->info('number rule init failed!');
            return;
        }
        $rules = [
            BaseConstService::BATCH_NO_TYPE => BaseConstService::BATCH,
            BaseConstService::ORDER_NO_TYPE => BaseConstService::TMS,
            BaseConstService::TOUR_NO_TYPE => BaseConstService::TOUR,
            BaseConstService::BATCH_EXCEPTION_NO_TYPE => BaseConstService::BATCH_EXCEPTION,
            BaseConstService::RECHARGE_NO_TYPE => BaseConstService::RECHARGE,
            BaseConstService::STOCK_EXCEPTION_NO_TYPE => BaseConstService::STOCK_EXCEPTION,
            BaseConstService::TRACKING_ORDER_NO_TYPE => BaseConstService::TRACKING_ORDER,
            BaseConstService::CAR_ACCIDENT_NO_TYPE => BaseConstService::CAR_ACCIDENT,
            BaseConstService::CAR_MAINTAIN_NO_TYPE => BaseConstService::CAR_MAINTAIN,
            BaseConstService::TRACKING_PACKAGE_NO_TYPE => BaseConstService::TRACKING_PACKAGE,
            BaseConstService::BAG_NO_TYPE => BaseConstService::BAG,
            BaseConstService::SHIFT_NO_TYPE => BaseConstService::SHIFT,
        ];
        foreach ($companyList as $company) {
            foreach (ConstTranslateTrait::$noTypeList as $k => $v) {
                $orderNoRule = OrderNoRule::query()->where('company_id', $company['id'])->where('type', $k)->first();
                if (empty($orderNoRule)) {
                    $prefix = $rules[$k] . $company['company_code'];
                    OrderNoRule::create([
                        'company_id' => $company['id'],
                        'type' => $k,
                        'prefix' => $prefix,
                        'start_index' => 1,
                        'int_length' => 7,
                        'max_no' => $prefix . str_repeat('9', 7)
                    ]);
                }
            }
        }
        $this->info('number rule init successful!');
    }
}
