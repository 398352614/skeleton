<?php

namespace App\Console\Commands;

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
        if (is_null($companyList)) {
            $this->info('number rule init failed!');
            return;
        }

        foreach ($companyList as $company) {
            foreach (ConstTranslateTrait::$noTypeList as $k => $v) {
                $orderNoRule = OrderNoRule::query()->where('company_id', $company['id'])->where('type', $k)->first();
                if (empty($orderNoRule)) {
                    $prefix = $k . $company['company_code'];
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
