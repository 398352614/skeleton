<?php


namespace App\Console\Commands;


use App\Models\Company;
use App\Models\OrderNoRule;
use App\Models\OrderTemplate;
use App\Services\BaseConstService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Console\Command;

class InitOrderTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:order-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'order template init';

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
        $companyIdList = Company::all()->pluck('id')->toArray();
        $orderTemplateList = OrderTemplate::all()->pluck('company_id')->toArray();
        foreach ($companyIdList as $companyId) {
            if (!in_array($companyId, $orderTemplateList)) {
                OrderTemplate::create([
                    'company_id' => $companyId,
                    'type' => BaseConstService::ORDER_TEMPLATE_TYPE_1,
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
                    'settlement_amount' => '运费金额'
                ]);
            }
        }
        $this->info('order template init successful!');
    }
}
