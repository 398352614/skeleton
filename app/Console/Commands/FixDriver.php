<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Driver;
use App\Models\OrderTemplate;
use App\Models\Warehouse;
use App\Services\BaseConstService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDriver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:driver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix driver table';

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
        foreach ($companyList as $k => $company) {
            $warehouseId = Warehouse::query()->where('company_id', $company['id'])->where('parent', '=', 0)->first()->toArray()['id'];

            Driver::query()->where('company_id', $company['id'])->update(['warehouse_id' => $warehouseId]);
        }
        OrderTemplate::query()->insert($data);
        $this->info('order template init successful!');
    }
}
