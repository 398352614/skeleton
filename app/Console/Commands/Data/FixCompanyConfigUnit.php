<?php

namespace App\Console\Commands\Data;

use App\Models\CompanyConfig;
use App\Services\BaseConstService;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixCompanyConfigUnit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:company-config-unit';

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
     */
    public function handle()
    {
        $data = CompanyConfig::all();

        foreach ($data as $datum) {
            $this->info('===== Company ID: ' . $datum['company_id'] . ' fixing =====');
            //判断重量单位
            switch ($datum['weight_unit']) {
                case 'kg' : {
                    $datum->weight_unit = BaseConstService::WEIGHT_UNIT_TYPE_1;
                }
                case 'lb': {
                    $datum->weight_unit = BaseConstService::WEIGHT_UNIT_TYPE_2;
                }
            }
            //判断货币单位
            switch ($datum['currency_unit']) {
                case '￥' : {
                    $datum->currency_unit = BaseConstService::CURRENCY_UNIT_TYPE_1;
                }
                case '$' : {
                    $datum->currency_unit = BaseConstService::CURRENCY_UNIT_TYPE_2;
                }
                case '€' : {
                    $datum->currency_unit = BaseConstService::CURRENCY_UNIT_TYPE_3;
                }
            }
            //判断重量单位
            switch ($datum['volume_unit']) {
                case 'cm³' : {
                    $datum->volume_unit = BaseConstService::VOLUME_UNIT_TYPE_1;
                }
                case 'm³' : {
                    $datum->volume_unit = BaseConstService::VOLUME_UNIT_TYPE_2;
                }
            }

            $datum->save();

            $this->info('===== Company ID: ' . $datum['company_id'] . ' fixed  =====');
        }

        //修改数据表字段类型
        Schema::table('company_config', function (Blueprint $table) {
            $table->smallInteger('weight_unit')->default(null)->nullable()->change();
            $table->smallInteger('currency_unit')->default(null)->nullable()->change();
            $table->smallInteger('volume_unit')->default(null)->nullable()->change();
        });
    }
}
