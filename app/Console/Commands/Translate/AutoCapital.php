<?php


namespace App\Console\Commands\Translate;


use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AutoCapital extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capital';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'auto capital';

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
            $this->autoCode();
            $this->info('The capital success.');
        } catch (\Exception $e) {
            $this->info('The capital fail:' . $e->getMessage());
        }
        return;
    }

    public function autoCode()
    {
        //1,正则匹配项目内所有文件以"throw new BusinessException('"开头，以"')"结尾的语句。
        //2,格式处理
        //3,写入文件
        $data = '';
        $params = file_get_contents('resources/lang/en.json');
        $params = collect(json_decode($params))->toArray();
        foreach ($params as $k => $v) {
            $params[$k] = ucfirst($v);
        }

        $json='{'."\n";
        foreach ($params as $k => $v) {
            $json .= '"' . $k . '":"' . $v . '",' . "\n";
        }
        $json=$json.'}';
        return file_put_contents('resources/lang/en1.json', $json);


        $oldJson = file_get_contents('resources/lang/en.json');
        for ($i = 0, $j = count($params); $i < $j; $i++) {
            $json .= '"' . $params[$i] . '":"' . $params[$i] . '",' . "\n";
        }
        $json = Str::replaceLast(',', '', $json);
        $oldJson = str_replace('}', '', $oldJson);
        $oldJson = $oldJson . ',' . $json . '}';

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
        if (!empty($diff)) {
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
