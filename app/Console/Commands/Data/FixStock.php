<?php

namespace App\Console\Commands\Data;

use App\Models\Company;
use App\Models\Driver;
use App\Models\OrderTemplate;
use App\Models\Stock;
use App\Models\StockInLog;
use App\Models\StockOutLog;
use App\Models\Warehouse;
use App\Services\BaseConstService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix stock table';

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
        $companyList = Company::query()->get(['*'])->toArray();
        foreach ($companyList as $k => $company) {
            $warehouseId = Warehouse::query()->where('company_id', $company['id'])->where('parent', '=', 0)->first()->toArray()['id'];
            Stock::query()->where('company_id', $company['id'])->update(['warehouse_id' => $warehouseId]);
            StockInLog::query()->where('company_id', $company['id'])->update(['warehouse_id' => $warehouseId]);
            StockOutLog::query()->where('company_id', $company['id'])->update(['warehouse_id' => $warehouseId]);
        }
        $this->info('Stock fix successful!');
    }
}
