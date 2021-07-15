<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Fee;
use App\Services\BaseConstService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Console\Command;

class InitFee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:fee';

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
            $sticker = Fee::query()->where('company_id', $company['id'])->where('code', BaseConstService::STICKER)->first();
            if (empty($sticker)) {
                Fee::create([
                    'company_id' => $company['id'],
                    'name' => '贴单费用',
                    'code' => BaseConstService::STICKER,
                    'level'=>1,
                    'amount' => 7.00
                ]);
            }
            $delivery = Fee::query()->where('company_id', $company['id'])->where('code', BaseConstService::DELIVERY)->first();
            if (empty($delivery)) {
                Fee::create([
                    'company_id' => $company['id'],
                    'name' => '提货费用',
                    'code' => BaseConstService::DELIVERY,
                    'level'=>1,
                    'amount' => 10.00
                ]);
            }
            $list = ConstTranslateTrait::orderAmountTypeList();
            foreach ($list as $k => $v) {
                $data[$k] = Fee::query()->where('company_id', $company['id'])->where('name', $v)->first();
                if (empty($data[$k])) {
                    Fee::create([
                        'company_id' => $company['id'],
                        'name' => $v,
                        'code' => $k,
                        'level'=>2,
                        'amount' => 0.00,
                        'is_valuable' => BaseConstService::YES,
                        'payer' => BaseConstService::FEE_PAYER_1,
                    ]);
                }
            }
        }
        $this->info('fee init successful!');
    }
}
