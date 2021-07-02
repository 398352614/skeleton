<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\OrderTemplate;
use App\Services\BaseConstService;
use Doctrine\DBAL\Driver\OCI8\Driver;
use Illuminate\Console\Command;

class ResetOrderTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:order-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset order template table';

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
        $data = [];
        $companyList = Company::query()->get(['*'])->toArray();
        OrderTemplate::query()->delete();
        foreach ($companyList as $k => $company) {
            $data[] = [
                'company_id' => $company['id'],
                'type' => BaseConstService::ORDER_TEMPLATE_TYPE_2,
                'is_default' => BaseConstService::ORDER_TEMPLATE_IS_DEFAULT_2,
                'logo' => '',
                'destination_mode' => BaseConstService::ORDER_TEMPLATE_DESTINATION_MODE_1,
                'sender' => '发件人',
                'receiver' => '收件人',
                'destination' => '目的地',
                'carrier' => '承运人',
                'carrier_address' => '承运人地址',
                'contents' => '物品信息',
                'package' => '包裹',
                'material' => '材料',
                'count' => '数量',
                'replace_amount' => '代收货款',
                'settlement_amount' => '运费金额',
                'created_at'=>now(),
                'updated_at'=>now()
            ];
            $data[] = [
                'company_id' => $company['id'],
                'type' => BaseConstService::ORDER_TEMPLATE_TYPE_1,
                'is_default' => BaseConstService::ORDER_TEMPLATE_IS_DEFAULT_1,
                'logo' => '',
                'destination_mode' => BaseConstService::ORDER_TEMPLATE_DESTINATION_MODE_1,
                'sender' => '发件人',
                'receiver' => '收件人',
                'destination' => '目的地',
                'carrier' => '承运人',
                'carrier_address' => '承运人地址',
                'contents' => '物品信息',
                'package' => '包裹',
                'material' => '材料',
                'count' => '数量',
                'replace_amount' => '代收货款',
                'settlement_amount' => '运费金额',
                'created_at'=>now(),
                'updated_at'=>now()
            ];
        }
        OrderTemplate::query()->insert($data);
        $this->info('order template init successful!');
    }
}
