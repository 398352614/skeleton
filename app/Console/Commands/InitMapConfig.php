<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Fee;
use App\Models\MapConfig;
use App\Services\BaseConstService;
use Illuminate\Console\Command;

class InitMapConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:map-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'company map config init';

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
        $companyList = Company::query()->get(['id'])->toArray();
        foreach ($companyList as $company) {
            $mapConfig = MapConfig::query()->where('company_id', $company['id'])->first();
            if (empty($mapConfig)) {
                MapConfig::create([
                    'company_id' => $company['id'],
                    'front_type' => BaseConstService::MAP_CONFIG_FRONT_TYPE_1,
                    'back_type' => BaseConstService::MAP_CONFIG_BACK_TYPE_1,
                    'mobile_type' => BaseConstService::MAP_CONFIG_MOBILE_TYPE_1,
                    'google_key' => '',
                    'google_secret' => '',
                    'baidu_key' => '',
                    'baidu_secret' => '',
                    'tencent_key' => '',
                    'tencent_secret' => '',
                ]);
            }
        }
        $this->info('company map config init successful!');
    }
}
