<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database';
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
        if (!file_exists(storage_path('app/backup'))) {
            $this->process = new Process(sprintf(
                'mkdir %s',
                storage_path('app/backup')
            ));
        }
        $this->process = new Process(sprintf(
            'mysqldump -h%s -u%s -p%s %s --ignore-table=%s --ignore-table=%s --ignore-table=%s | gzip > %s',
            config('database.connections.mysql.host'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),

            config('database.connections.mysql.database') . '.telescope_entries',
            config('database.connections.mysql.database') . '.telescope_entries_tags',
            config('database.connections.mysql.database') . '.telescope_monitoring',

            storage_path('app/backup/backup.sql.gz')
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
            $this->process->mustRun();
            $this->info(now()->format('Y-m-d H:i:s ') . 'The backup has been proceed successfully.');
        } catch (ProcessFailedException $exception) {
            $this->error(now()->format('Y-m-d H:i:s ') . 'The backup process has been failed.');
        }
    }
}
