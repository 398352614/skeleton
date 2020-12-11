<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class fillAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'all:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'second all fill';

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
        $startTime = time();
        //初始化单号规则
        Artisan::call('no-rule:init');
        //创建运单相关表
        Artisan::call('migrate --path="database/migrations/2020_12_10_170523_create_new_tables.php"');
        //填充运单相关数据
        Artisan::call('tracking-order:init');
        //修改订单相关表
        Artisan::call('migrate --path="database/migrations/2020_12_11_141735_alter_order_tables.php"');
        //运单相关数据填充
        Artisan::call('tracking-order:fill');

        $time = (time() - $startTime) / 60;
        $this->info("successful,the time is {$time} min");
        return;
    }
}
