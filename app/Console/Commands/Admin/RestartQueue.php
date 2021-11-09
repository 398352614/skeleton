<?php

namespace App\Console\Commands\Admin;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RestartQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restart:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart queue';
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
        $this->process = new Process('supervisorctl restart all');
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
            $this->info(now()->format('Y-m-d H:i:s ') . 'The restart has been proceed successfully.');
        } catch (ProcessFailedException $exception) {
            $this->error(now()->format('Y-m-d H:i:s ') . 'The restart process has been failed.');
        }
    }
}
