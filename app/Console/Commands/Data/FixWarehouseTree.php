<?php

namespace App\Console\Commands\Data;

use App\Models\Company;
use App\Models\Warehouse;
use Illuminate\Console\Command;

class FixWarehouseTree extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:warehouse-tree {--id= : company id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        $company = Company::query()->get(['id'])->toArray();

        $warehouseRootNode = [];

        $this->info('start find warehouse root');
        foreach ($company as $item) {
            $warehouseRootId = Warehouse::query()
                ->where('company_id', $item['id'])
                ->orderBy('id', 'asc')
                ->value('id');

            $warehouseRootNode[$item['id']] = $warehouseRootId;
        }

        $this->info('start fix warehouse parent');
        foreach ($warehouseRootNode as $k => $v) {
            if (is_null($v)) {
                continue;
            }

            Warehouse::query()->where('company_id', $k)
                ->where('id', '!=', $v)
                ->update(['parent' => $v]);
        }

        Warehouse::deleteRedundancies();

        $warehouses = Warehouse::all();

        $this->info('start fix warehouse tree');
        foreach ($warehouses as $warehouse) {
            $warehouse->perfectNode();
            $warehouse->perfectTree();
        }
    }
}
