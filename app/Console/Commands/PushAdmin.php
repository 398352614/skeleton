<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use WebSocket\Client;

class PushAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:push {id : the admin u_id} {type : the push type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'websocket push news';

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
     * @return bool
     * @throws \WebSocket\BadOpcodeException
     * @throws \WebSocket\ConnectionException
     */
    public function handle()
    {
        $data = $this->ask('please input data(JSON format)');
        var_dump($data);
        if (!isJson($data)) {
            $this->error('The data must be JSON format');
            exit;
        }
        $id = $this->argument('id');
        $type = $this->argument('type');
        $user = Employee::query()->where('id', $id)->first();
        if (empty($user)) {
            $this->error('The id employee dose not exist');
            exit;
        }
        $token = Auth::guard('admin')->login($user);
        $client = new Client('wss://dev-tms.nle-tech.com/socket/?token=' . $token);
        $client->send('{"type":"notifyDriver","data":{"u_id":4,"content":"hello"}}');
        $receive = $client->receive();
        $this->info('receive' . $receive);
        $this->info('push successful');
        return true;
    }
}
