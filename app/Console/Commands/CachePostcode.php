<?php


namespace App\Console\Commands;


use App\Models\AddressTemplate;
use App\Traits\PostcodeTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CachePostcode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postcode:cache';

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
