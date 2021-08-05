<?php

namespace App\Console\Commands\Data;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class FixPackageMaterial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:package-material';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Figure out package material';
    /**
     * @var Process
     */
    private $process;

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
        try {
            $orderList = DB::table('order')->get();
            foreach ($orderList as $v) {
                DB::table('package')->where('order_no', $v->order_no)->update(['execution_date' => $v->execution_date, 'merchant_id' => $v->merchant_id]);
                DB::table('material')->where('order_no', $v->order_no)->update(['execution_date' => $v->execution_date, 'merchant_id' => $v->merchant_id]);
            }
            $this->info('successful');
        } catch (\Exception $e) {
            $this->info('failed');
        }
    }
}
