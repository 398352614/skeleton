<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Fee;
use App\Services\BaseConstService;
use Illuminate\Console\Command;

class initFee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fee:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'company fee init';

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
        $companyList = Company::query()->get(['id'])->toArray();
        foreach ($companyList as $company) {
            $fee = Fee::query()->where('company_id', $company['id'])->first();
            if (!empty($fee)) continue;
            Fee::create([
                'company_id' => $company['id'],
                'name' => '贴单费用',
                'code' => BaseConstService::STICKER,
                'amount' => 7.00
            ]);
        }
        $this->info('fee init successful!');
    }
}
