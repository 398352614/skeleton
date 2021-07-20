<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UpdateWarehouseVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:warehouse-version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'warehouse version update';

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
        $this->info('all start');
        $array = [
            'init:base-permission',
            'init:map-config',
            'init:order-template',
            'reset:order-template',
            'fix:warehouse',
            'fix:company-config-unit',
            'fix:driver',
            'fix:package',
            'fix:address',
            'fix:stock'
        ];
        foreach ($array as $k => $v) {
            $this->info($v . ' start');
            Artisan::call($v);
            $this->info($v . ' end');
        }
        $this->info('all end');
    }
}
