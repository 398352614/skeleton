<?php

namespace App\Console\Commands\backup;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RecoverDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:recover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recover the database';
    /**
     * @var Process
     */
    private $process;
    /**
     * @var Process
     */
    private $process1;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {



        parent::__construct();

//        $this->process = new Process(sprintf(
//            'mysql -h%s -u%s -p%s %s < %s',
//            config('database.connections.mysql.host'),
//            config('database.connections.mysql.username'),
//            config('database.connections.mysql.password'),
//            config('database.connections.mysql.database'),
//            config('tms.db_backup')
//        ));
//        $this->process = new Process(sprintf(
//            'gunzip -c %s > %s|mysql -h%s -u%s -p%s %s < %s',
//            config('tms.db_backup').'.gz',
//            config('tms.db_backup'),
//            config('database.connections.mysql.host'),
//            config('database.connections.mysql.username'),
//            config('database.connections.mysql.password'),
//            config('database.connections.mysql.database'),
//            storage_path(config('tms.db_backup'))
//        ));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::unprepared(file_get_contents(config('tms.db_backup')));

//        try {
//            if(file_exists(storage_path('app/backup/backup.sql.gz'))){
//                DB::unprepared(file_get_contents(config('tms.db_backup')));
//                $this->info(now()->format('Y-m-d H:i:s ') . 'The recover has been proceed successfully.');
//            }else{
//                $this->info(now()->format('Y-m-d H:i:s ') . 'The recover does mot exist.');
//            }
//        } catch (ProcessFailedException $exception) {
//            Log::channel('schedule')->error(__CLASS__ .'.'. __FUNCTION__ .'.'. 'exception',collect($exception)->toArray());
//            $this->error(now()->format('Y-m-d H:i:s ') . 'The recover process has been failed.');
//        }catch (\Exception $exception){
//            Log::channel('schedule')->error(__CLASS__ .'.'. __FUNCTION__ .'.'. 'exception',collect($exception)->toArray());
//        }
    }
}
