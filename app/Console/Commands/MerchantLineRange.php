<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Fee;
use App\Models\Line;
use App\Models\LineRange;
use App\Services\BaseConstService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MerchantLineRange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merchantLineRange:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'merchant line range init';

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
        $merchantList = DB::table('merchant')->get()->toArray();
        $model = new \App\Models\MerchantLineRange();
        foreach ($merchantList as $merchant) {
            $lineRangeList = LineRange::query()->where('company_id', $merchant->company_id)->get()->toArray();
            foreach ($lineRangeList as $key => $lineRange) {
                unset($lineRangeList[$key]['id'], $lineRangeList[$key]['country_name'], $lineRangeList[$key]['is_split']);
            }
            data_set($lineRangeList, '*.merchant_id', $merchant->id);
            $model->insertAll($lineRangeList);
        }
        $this->info('merchant line range init successful');
    }
}
