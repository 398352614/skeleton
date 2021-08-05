<?php

namespace App\Console\Commands\Admin;

use App\Traits\TourRedisLockTrait;
use Illuminate\Console\Command;

class UnlockTour extends Command
{
    use TourRedisLockTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unlock:tour {tour_no}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '解锁 tour 的操作锁定';

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
        $tour_no = $this->argument('tour_no');
        self::setTourLock($tour_no, 0);
    }
}
