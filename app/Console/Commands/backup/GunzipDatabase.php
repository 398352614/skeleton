<?php

namespace App\Console\Commands\backup;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class GunzipDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:gunzip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gunzip the database';
    /**
     * @var Process
     */
    private $process;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->process = new Process(sprintf(
            'gunzip -c %s > %s',
            config('tms.db_backup').'.gz',
            config('tms.db_backup')
        ));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            if(file_exists(storage_path('app/backup/backup.sql.gz'))){
                $this->process->mustRun();
                $this->info(now()->format('Y-m-d H:i:s ') . 'The gunzip has been proceed successfully.');
            }else{
                $this->info(now()->format('Y-m-d H:i:s ') . 'The gunzip does mot exist.');
            }
        } catch (ProcessFailedException $exception) {
            Log::channel('schedule')->error(__CLASS__ .'.'. __FUNCTION__ .'.'. 'exception',collect($exception)->toArray());
            $this->error(now()->format('Y-m-d H:i:s ') . 'The gunzip process has been failed.');
        }
    }
}
