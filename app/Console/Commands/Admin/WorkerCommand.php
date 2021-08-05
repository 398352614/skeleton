<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/4/7
 * Time: 16:27
 */

namespace App\Console\Commands\Admin;


use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Illuminate\Console\Command;
use Workerman\Worker;

class WorkerCommand extends Command
{
    protected $signature = 'worker {action} {--d}';

    protected $description = 'Start a Workerman server.';

    protected $registerAddress = '127.0.0.1:1236';

    public function handle()
    {
        global $argv;
        $action = $this->argument('action');

        $argv[0] = 'wk';
        $argv[1] = $action;
        $argv[2] = $this->option('d') ? '-d' : '';

        $this->start();
    }

    private function start()
    {
        Worker::$stdoutFile = '/tmp/workerman_stdout.log';
        Worker::$logFile = '/tmp/workerman.log';
        $this->startGateWay();
        $this->startBusinessWorker();
        $this->startRegister();
        Worker::runAll();
    }

    private function startBusinessWorker()
    {
        $worker = new BusinessWorker();
        $worker->name = 'BusinessWorker';
        $worker->count = 1;
        $worker->registerAddress = $this->registerAddress;
        $worker->eventHandler = \App\Worker\Events::class;
    }

    private function startGateWay()
    {
        $gateway = new Gateway("websocket://0.0.0.0:2346");
        $gateway->name = 'Gateway';
        $gateway->count = 1;
        $gateway->lanIp = '127.0.0.1';
        $gateway->startPort = 2300;
        $gateway->pingInterval = 30;
        $gateway->pingNotResponseLimit = 1;
        $gateway->pingData = '{"type":"heart"}';
        $gateway->registerAddress = $this->registerAddress;
    }

    private function startRegister()
    {
        new Register('text://0.0.0.0:1236');
    }
}
