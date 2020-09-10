<?php


namespace App\Console\Commands;


use App\Traits\ConstTranslateTrait;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function Composer\Autoload\includeFile;
use function GuzzleHttp\Psr7\str;

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
        Log::info('The translation begin.');
        try {
            $this->autoValidate();
            $this->info('The translation success.');
        } catch (Exception $e) {
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
        $array = include "/Users/whoyummy/Documents/tms-api/resources/lang/cn/validation.php";
        $attributes = $array['attributes'];
        $result = array_diff($attributes, $data);
        for ($i = 0, $j = count($result); $i < $j; $i++) {
            $json .= '"' . $result[$i]['src'] . '":"' . $result[$i]['dst'] . '",' . "\n";
        }
        $json=Str::replaceLast(',','',$json);

    }
}
