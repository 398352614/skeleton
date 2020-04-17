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
        $data = $this->ask('please input data(Array format)');
        if (!is_array($data)) {
            $this->error('The data must be Array format');
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
        $message = ['type' => $type, 'data' => $data];
        $client = new Client('wss://' . config('tms.push_url') . '/?token=' . $token);
        $client->send(json_encode($message, JSON_UNESCAPED_UNICODE));
        $client->close();
        $this->info('push successful');
        return true;
    }
}
