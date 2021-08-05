<?php

namespace App\Console\Commands\Data;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:address';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix address table';

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
            $address = DB::table('address')->get();
            foreach ($address as $k => $v) {
                $v = collect($v)->toArray();
                $uniqueCode = md5(
                    $v['type'] .
                    $v['company_id'] .
                    $v['merchant_id'] .
                    $v['place_fullname'] .
                    $v['place_phone'] .
                    $v['place_country'] .
                    $v['place_province'] .
                    $v['place_city'] .
                    $v['place_district'] .
                    $v['place_post_code'] .
                    $v['place_street'] .
                    $v['place_house_number'] .
                    $v['place_address']);
                if (DB::table('address')->where('id', '<>', $v['id'])->where('unique_code',$uniqueCode)->get()->isNotEmpty()) {
                    DB::table('address')->where('id', '<>', $v['id'])->where('unique_code',$uniqueCode)->delete();
                }
                DB::table('address')->where('id', $v['id'])->update(['unique_code' => $uniqueCode]);
            }
        } catch (\Exception $e) {
            $this->info('fix fail:' . $e);
        }
        $this->info('fix end');
    }
}
