<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixWarehouse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:warehouse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix warehouse table';

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
            //给每个公司新增一个根网点
            //给每个员工填充根网点
            //给每个货主填充根网点
        } catch (\Exception $e) {
            $this->info('fix fail:' . $e);
        }
        $this->info('fix end');
    }
}
