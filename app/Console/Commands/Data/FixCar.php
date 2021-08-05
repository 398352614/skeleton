<?php

namespace App\Console\Commands\Data;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixCar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:car';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix car table';

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
            $car = DB::table('car')->get()->toArray() ?? [];
            foreach ($car as $k => $v) {
                if (!empty($v->relate_material)) {
                    $materialList = ['material_name' => $v->relate_material_name, 'material_url' => $v->relate_material];
                    DB::table('car')->where('id', $v->id)->update(['relate_material_list' => [$materialList]]);
                }
            }
        } catch (\Exception $e) {
            $this->info('fix fail:' . $e);
        }
        $this->info('fix end');
    }
}
