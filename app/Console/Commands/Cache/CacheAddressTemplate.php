<?php

namespace App\Console\Commands\Cache;

use App\Models\AddressTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheAddressTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:address-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'address template list cache';

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
        //获取配置
        $rootKey = config('tms.cache_prefix.address_template');
        $tag = config('tms.cache_tags.address_template');
        //缓存
        try {
            $addressTemplateList = AddressTemplate::query()->get(['id', 'template'])->toArray();
            foreach ($addressTemplateList as $template) {
                Cache::tags($tag)->forget($rootKey . $template['id']);
                Cache::tags($tag)->forever($rootKey . $template['id'], $template);
            }
            $this->info('address template list cache successful');
            return;
        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
            return;
        }
    }
}
