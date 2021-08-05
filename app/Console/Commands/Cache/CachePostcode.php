<?php


namespace App\Console\Commands\Cache;


use App\Traits\PostcodeTrait;
use Illuminate\Console\Command;

class CachePostcode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:postcode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'postcode of China cache';

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
        PostcodeTrait::initPostcodeList();
    }
}
