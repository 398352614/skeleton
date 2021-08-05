<?php

namespace App\Console\Commands\Data;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixRechargeStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:recharge-statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix recharge statistics table';

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
        $this->info('fix begin');
        try {
            $rechargeStatisticsList = DB::table('recharge_statistics')->get();
            foreach ($rechargeStatisticsList as $k => $v) {
                DB::table('recharge_statistics')->where('id', $v->id)->update(['execution_date' => $v->recharge_date]);
            }
        } catch (\Exception $e) {
            $this->info('fix fail:' . $e);
        }
        $this->info('fix end');

        /*        $this->info('fix begin');
                try {
                    $driverList = DB::table('driver')->get() ?? [];
                    $rechargeList = DB::table('recharge')->whereNotNull('recharge_date')->get();
                    foreach ($rechargeList as $k => $v) {
                        $v = collect($v)->toArray();
                        if (empty($v)) {
                            throw new BusinessLogicException('1');
                        }
                        $info = DB::table('recharge_statistics')
                            ->where('company_id', $v['company_id'])
                            ->where('merchant_id', $v['merchant_id'])
                            ->where('recharge_date', $v['recharge_date'])
                            ->where('driver_id', $v['driver_id'])
                            ->first();
                        $info = collect($info)->toArray();
                        if (empty($info)) {
                            $info['id'] = DB::table('recharge_statistics')->insertGetId([
                                'company_id' => $v['company_id'],
                                'merchant_id' => $v['merchant_id'],
                                'recharge_date' => $v['recharge_date'],
                                'driver_id' => $v['driver_id'],
                                'driver_name' => $driverList->where('id', $v['driver_id'])->where('company_id', $v['company_id'])->first()->fullname,
                                'total_recharge_amount' => 0,
                                'recharge_count' => 0,
                                'status' => BaseConstService::RECHARGE_VERIFY_STATUS_1,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                        $totalRechargeAmount = $rechargeList
                            ->where('company_id', $v['company_id'])
                            ->where('merchant_id', $v['merchant_id'])
                            ->where('recharge_date', $v['recharge_date'])
                            ->where('driver_id', $v['driver_id'])
                            ->sum('recharge_amount');
                        $rechargeCount = $rechargeList
                            ->where('company_id', $v['company_id'])
                            ->where('merchant_id', $v['merchant_id'])
                            ->where('recharge_date', $v['recharge_date'])
                            ->where('driver_id', $v['driver_id'])
                            ->count();
                        DB::table('recharge_statistics')->where('id', $info['id'])->update(['total_recharge_amount' => $totalRechargeAmount, 'recharge_count' => $rechargeCount]);
                        DB::table('recharge')->where('id', $v['id'])->update(['recharge_statistics_id' => $info['id']]);
                    }
                } catch (\Exception $e) {
                    $this->info('fix fail:' . $e);
                }
                $this->info('fix end');*/
    }
}
