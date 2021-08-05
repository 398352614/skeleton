<?php

namespace App\Console\Commands\Data;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixRecharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:recharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix recharge table';

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
            //处理流水表
            $rechargeList = DB::table('recharge')->get()->toArray();
            foreach ($rechargeList as $k => $v) {
                $rechargeList[$k] = collect($rechargeList[$k])->toArray();
                $rechargeList[$k]['tour_list'] = DB::table('tour')->where('execution_date', $rechargeList[$k]['recharge_date'])->where('driver_id', $rechargeList[$k]['driver_id'])->get();
                if (count($rechargeList[$k]['tour_list']) == 1) {
                    DB::table('recharge')->where('id', $rechargeList[$k]['id'])->update(
                        [
                            'tour_no' => $rechargeList[$k]['tour_list'][0]->tour_no,
                            'execution_date' => $rechargeList[$k]['tour_list'][0]->execution_date,
                            'line_id' => $rechargeList[$k]['tour_list'][0]->line_id,
                            'line_name' => $rechargeList[$k]['tour_list'][0]->line_name
                        ]);
                }
            }
        } catch (\Exception $e) {
            $this->info('fix fail:' . $e);
        }
        $this->info('fix end');
    }
}
