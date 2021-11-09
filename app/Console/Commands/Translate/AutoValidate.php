<?php


namespace App\Console\Commands\Translate;


use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AutoValidate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'auto validate';

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
        try {
            $this->autoValidate();
            $this->info('The translation success.');
        } catch (\Exception $e) {
            $this->info('The translation fail:' . $e->getMessage());
        }
        return;
    }

    public function autoValidate()
    {
        $data = [];
        $row = [];
        $json = '';
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        foreach ($tables as $k => $v) {
            $row = array_merge($row, DB::select("SHOW FULL COLUMNS FROM `{$v}`"));
        }
        foreach ($row as $k => $v) {
            $v = collect($v)->toArray();
            $data[$v['Field']] = $v['Comment'];
        }
        foreach ($data as $k => $v) {
            $data[$k] = explode('1-', $v)[0];
        }
        $array = include base_path('resources/lang/cn/validation.php');
        $attributes = $array['attributes'];
        $key = array_keys($attributes);
        $result = Arr::except($data, $key);
        if (!empty($result)) {
            foreach ($result as $k => $v) {
                $json .= '"' . $k . '"=>"' . $v . '",' . "\n";
            }
            $oldJson = Str::replaceLast('];', '', file_get_contents('resources/lang/cn/validation.php'));
            $oldJson = Str::replaceLast(']', '', $oldJson);
            $oldJson = $oldJson . $json . ']' . "\n" . '];';
            file_put_contents('resources/lang/cn/validation.php', $oldJson);


        }
        $json = '';
        $params = [];
        $array = include base_path('resources/lang/en/validation.php');
        $diff = Arr::except($data, array_keys($array['attributes']));
        if(!empty($diff)){
            foreach ($diff as $k => $v) {
                $params[$k] = str_replace('_', ' ', $k);
            }
            foreach ($params as $k => $v) {
                $json .= '"' . $k . '"=>"' . $v . '",' . "\n";
            }
            $oldJson = Str::replaceLast('];', '', file_get_contents('resources/lang/en/validation.php'));
            $oldJson = Str::replaceLast(']', '', $oldJson);
            $oldJson = $oldJson . $json . ']' . "\n" . '];';
            file_put_contents('resources/lang/en/validation.php', $oldJson);
        }
    }
}
