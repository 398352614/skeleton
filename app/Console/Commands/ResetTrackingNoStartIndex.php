<?php

namespace App\Console\Commands;

use App\Models\OrderNoRule;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ResetTrackingNoStartIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking-no-index:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'tracking no start index reset';

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
        $trackingOrderRuleNoList = OrderNoRule::query()->where('type', BaseConstService::TRACKING_ORDER_NO_TYPE)->get()->toArray();
        foreach ($trackingOrderRuleNoList as $trackingOrderNoRule) {
            $orderNoRule = OrderNoRule::query()->where('company_id', $trackingOrderNoRule['company_id'])->where('type', BaseConstService::ORDER_NO_TYPE)->first();
            if (empty($orderNoRule)) continue;
            OrderNoRule::query()->where('id', $trackingOrderNoRule['id'])->update(['start_index' => intval($orderNoRule['start_index'])]);
        }
        $this->info('successful');
        return;
    }
}
