<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Line;
use App\Models\MapConfig;
use App\Models\Warehouse;
use App\Services\BaseConstService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixLine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:line';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix line table';

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
            $companyList = Company::query()->get()->toArray();
            foreach ($companyList as $company) {
                $warehouse = Warehouse::query()->where('company_id', $company['id'])->first();
                Line::query()->where('company_id', $company['id'])->update(['warehouse_id' => $warehouse['id']]);
            }
            //给每个员工填充根网点
        } catch (\Exception $e) {
            $this->info('fix fail:' . $e);
        }
        $this->info('fix end');
    }
}
