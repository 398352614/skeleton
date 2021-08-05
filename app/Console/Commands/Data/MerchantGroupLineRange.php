<?php

namespace App\Console\Commands\Data;

use App\Models\LineRange;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MerchantGroupLineRange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:merchantGroupLineRange';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'merchant group line range init';

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
        $merchantGroupList = DB::table('merchant_group')->get()->toArray();
        $model = new \App\Models\MerchantGroupLineRange();
        foreach ($merchantGroupList as $merchantGroup) {
            $lineRangeList = LineRange::query()->where('company_id', $merchantGroup->company_id)->get()->toArray();
            foreach ($lineRangeList as $key => $lineRange) {
                unset($lineRangeList[$key]['id'], $lineRangeList[$key]['country_name']);
            }
            data_set($lineRangeList, '*.merchant_group_id', $merchantGroup->id);
            $model->insertAll($lineRangeList);
        }
        $this->info('merchant group line range init successful');
    }
}
