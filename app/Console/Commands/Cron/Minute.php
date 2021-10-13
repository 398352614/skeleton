<?php

namespace App\Console\Commands\Cron;

use App\Jobs\AutoBillVerify;
use App\Models\BillVerify;
use App\Models\Merchant;
use App\Services\BaseConstService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Minute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:minute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cron minute';

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
        $this->autoBillVerify();
    }

    public function autoBillVerify()
    {
        $merchantList = Merchant::query()->where('status', BaseConstService::YES)->where('auto_settlement', BaseConstService::YES)->get();
        foreach ($merchantList as $k => $v) {
            if (
                ($v['settlement_type'] == BaseConstService::MERCHANT_SETTLEMENT_TYPE_2
                    && !empty($v['settlement_time'])
                    && $v['settlement_time'] < now()->format('H:i')) or
                ($v['settlement_type'] == BaseConstService::MERCHANT_SETTLEMENT_TYPE_3
                    && !empty($v['settlement_week'])
                    && $v['settlement_week'] == now()->dayOfWeek) or
                ($v['settlement_type'] == BaseConstService::MERCHANT_SETTLEMENT_TYPE_4
                    && !empty($v['settlement_date'])
                    && $v['settlement_date'] == now()->day)
            ) {
                if ($v['last_settlement_date'] < today()->format('Y-m-d')) {
                    Log::channel('roll')->info(__CLASS__ .'.'. __FUNCTION__ .'.'. '$merchantList', collect($v)->toArray());
                    dispatch(new AutoBillVerify($v['id']));
                    Log::channel('roll')->notice('结束');
                }
            }
        }
    }
}
