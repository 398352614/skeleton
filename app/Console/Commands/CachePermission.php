<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CachePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cache permission list successful';

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
        $tag = config('tms.cache_tags.permission');
        Cache::tags($tag)->flush();
        $permissionList = Permission::query()->get()->toArray();
        Cache::tags($tag)->forever('permission_list', $permissionList);
        $this->info('cache menu list successful');
        return $permissionList;
    }
}
