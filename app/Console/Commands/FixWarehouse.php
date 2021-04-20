<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Employee;
use App\Models\MapConfig;
use App\Models\Warehouse;
use App\Services\BaseConstService;
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
            $companyList = Company::query()->get()->toArray();
            foreach ($companyList as $company) {
                $warehouse = Warehouse::query()->where('company_id', $company['id'])->first();
                if (empty($warehouse)) {
                    $warehouse = Warehouse::create([
                        'name' => $company['email'],
                        'company_id' => $company['id'],
                        'type' => BaseConstService::WAREHOUSE_TYPE_2,
                        'is_center' => BaseConstService::NO,
                        'acceptance_type' => BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_1 . ',' . BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_2 . ',' . BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_3,
                        'line_ids' => '',
                        'parent' => 0
                    ]);
                }
                Employee::query()->where('company_id', $company['id'])->update(['warehouse_id' => $warehouse['id']]);
            }
            //给每个员工填充根网点
        } catch (\Exception $e) {
            $this->info('fix fail:' . $e);
        }
        $this->info('fix end');
    }
}
