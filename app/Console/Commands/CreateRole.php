<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;

class CreateRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:role
                                        {--company_id= : company id}
                                        {--name= : name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create company role';

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
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function handle()
    {
        $companyId = $this->option('company_id');
        if (empty($companyId)) {
            $this->error('please input company id');
            exit;
        }
        $name = $this->option('name');
        if (empty($name)) {
            $this->error('please input name');
            exit;
        }
        Role::create(['company_id' => $companyId, 'name' => $name]);
        $this->info('successful');
    }
}
