<?php

namespace App\Console\Commands\Cache;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheDestroy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:destroy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cache destroy';

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
        Cache::flush();
        $this->info('flush cache successful!');
    }
}
