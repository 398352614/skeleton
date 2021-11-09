<?php


namespace App\Console\Commands\Data;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FixValidate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:validate';

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
            $this->fixValidate();
            $this->info('The translation success.');
        } catch (\Exception $e) {
            $this->info('The translation fail:' . $e->getMessage());
        }
        return;
    }

    public function fixValidate()
    {
        $validationCn = include base_path('resources/lang/cn/validation.php');
        $validationEn = include base_path('resources/lang/en/validation.php');

        $translationEn = collect(json_decode(file_get_contents('resources/lang/en.json')))->toArray();
        $validationCn = $validationCn['attributes'];
        $validationEn = $validationEn['attributes'];

        foreach ($validationEn as $k => $v) {
            $validationEn[$k] = $translationEn[$validationCn[$k]] ?? $v;
        }
        $json='';
        if (!empty($validationEn)) {
            foreach ($validationEn as $k => $v) {
                $json .= '"' . $k . '"=>"' . $v . '",' . "\n";
            }
            $oldJson = Str::replaceLast('];', '', file_get_contents('resources/lang/en/validation.php'));
            $oldJson = Str::replaceLast(']', '', $oldJson);
            $oldJson = $oldJson . $json . ']' . "\n" . '];';
            file_put_contents('resources/lang/cn/validation.php', $oldJson);
        }
    }
}
