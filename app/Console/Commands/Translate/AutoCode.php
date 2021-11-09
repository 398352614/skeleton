<?php


namespace App\Console\Commands\translate\Translate;


use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AutoCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'auto code';

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
            $this->info('The code success.');
        } catch (\Exception $e) {
            $this->info('The code fail:' . $e->getMessage());
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
            $data .= '"' . $k . '"=>"' . $v . '",' . "\n";
        }
        $oldJson = Str::replaceLast('];', '', file_get_contents('app/Exceptions/ErrorCode.php'));
        $oldJson = Str::replaceLast(']', '', $oldJson);
        $oldJson = $oldJson . $data . ']' . "\n" . '];';
        file_put_contents('app/Exceptions/ErrorCode.php', $oldJson);

    }
}
