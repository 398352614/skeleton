<?php


namespace App\Console\Commands\Translate;


use App\Traits\ConstTranslateTrait;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AutoTranslate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'auto translate';

    protected $id;

    protected $key;

    protected $url;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->id = config('tms.baidu_id');
        $this->key = config('tms.baidu_key');
        $this->url = config('tms.baidu_url');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
//        try {
        foreach (array_keys(ConstTranslateTrait::$languageList) as $v) {
            if ($v !== 'cn') {
                $this->phpToText($v);
            }
        }
        $this->info('The translation success.');
//        } catch (Exception $e) {
//            $this->info('The translation fail:' . $e->getMessage());
//        }
        return;
    }

    /**
     * @param $language
     * @return string
     * @throws Exception
     */
    public function phpToText($language)
    {
        $txt = '';
        $path = app_path(); // 需要转换的文件路径。
        $toPath = base_path('resources/lang/' . $language . '.json');  // 最终要放到的位置。
        $stringArr = $this->get_filenamesbydir($path);
        foreach ($stringArr as $name => $content) {
            $txt = $txt . PHP_EOL . $name . PHP_EOL . PHP_EOL . $content;
        }
        $allChinese = $this->findChinese($txt);
        $oldChinese = array_keys(json_decode(file_get_contents('resources/lang/' . $language . '.json'), true));
        $transChinese = array_diff($allChinese, $oldChinese);
        $transTxt = '';
        foreach ($transChinese as $k => $v) {
            $transTxt .= $v . "\n";
        }
        if ($transTxt !== '') {
            $result = $this->translate($transTxt, $language);
            $json = '';
            $oldJson = file_get_contents('resources/lang/' . $language . '.json');
            for ($i = 0, $j = count($result); $i < $j; $i++) {
                $json .= '"' . $result[$i]['src'] . '":"' . ucfirst($result[$i]['dst']) . '",' . "\n";
            }
            $json = Str::replaceLast(',', '', $json);
            $oldJson = str_replace('}', '', $oldJson);
            $oldJson = $oldJson . ',' . $json . '}';
            $row = file_put_contents($toPath, $oldJson);
            if (!empty($row)) {
                return 'success';
            }
        }
        return 'fail';
    }

    /**
     * 递归获取文件
     * @param $path
     * @param $files
     * @param $stringArr
     */
    public function get_allfiles($path, &$files, &$stringArr)
    {
        if (is_dir($path)) {
            $dp = dir($path);
            while ($file = $dp->read()) {
                if ($file !== "." && $file !== "..") {
                    $this->get_allfiles($path . "/" . $file, $files, $stringArr);
                }
            }
            $dp->close();
        }
        if (is_file($path)) {
            $files[] = $path;
            $stringArr[$path] = file_get_contents($path);
        }
    }

    /**
     * 获取文件夹下所有文件
     * @param $dir
     * @return array
     */
    public function get_filenamesbydir($dir)
    {
        $files = $stringArr = array();
        $this->get_allfiles($dir, $files, $stringArr);
        return $stringArr;
    }


    //提取中文
    public function findChinese($txt)
    {
        $array = [];
        //preg_match_all("/\"[\x7f-\xff](.*)\"/", $txt, $x);

        preg_match_all("/'[\x7f-\xff](.*)'/U", $txt, $x);
        foreach (array_unique($x[0]) as $v) {
            $v = str_replace("'", '', $v);
            $v = str_replace('"', '', $v);
            $array[] = $v;
        }
        preg_match_all("/\"[\x7f-\xff](.*)\"/U", $txt, $x);
        foreach (array_unique($x[0]) as $v) {
            $v = str_replace("'", '', $v);
            $v = str_replace('"', '', $v);
            $array[] = $v;
        }
        return $array;
    }

    /**
     * 翻译
     * @param string $txt
     * @param $language
     * @return array
     * @throws Exception
     */
    public function translate(string $txt, $language)
    {
        $info = $this->translateApi($txt, 'zh', $language);
        if (!empty($info['error_code'])) {
            if ($info['error_code'] == 58000) {
                throw new Exception('IP不对');
            }
            throw new Exception('API错误码:' . $info['error_code']);
        }

        return $info['trans_result'];
    }

    /**
     * 翻译入口
     * @param $query
     * @param $from
     * @param $to
     * @return bool|int|mixed|string
     */
    public function translateApi($query, $from, $to)
    {
        $args = array(
            'q' => $query,
            'appid' => $this->id,
            'salt' => rand(10000, 99999),
            'from' => $from,
            'to' => $to,

        );
        $args['sign'] = $this->buildSign($query, $this->id, $args['salt'], $this->key);
        $ret = $this->call($this->url, $args);
        $ret = json_decode($ret, true);
        return $ret;
    }

    //加密
    public function buildSign($query, $appID, $salt, $secKey)
    {
        $str = $appID . $query . $salt . $secKey;
        return md5($str);
    }

    //发起网络请求
    public function call($url, $args = null, $method = "post", $timeout = 10, $headers = array())
    {
        $ret = false;
        $i = 0;
        while ($ret === false) {
            if ($i > 1)
                break;
            if ($i > 0) {
                sleep(1);
            }
            $ret = $this->callOnce($url, $args, $method, false, $timeout, $headers);
            $i++;
        }
        return $ret;
    }

    public function callOnce($url, $args = null, $method = "post", $withCookie = false, $timeout = 10, $headers = array())
    {
        $ch = curl_init();
        if ($method == "post") {
            $data = $this->convert($args);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, 1);
        } else {
            $data = $this->convert($args);
            if ($data) {
                if (stripos($url, "?") > 0) {
                    $url .= "&$data";
                } else {
                    $url .= "?$data";
                }
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($withCookie) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
        }
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }

    public function convert(&$args)
    {
        $data = '';
        if (is_array($args)) {
            foreach ($args as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        $data .= $key . '[' . $k . ']=' . rawurlencode($v) . '&';
                    }
                } else {
                    $data .= "$key=" . rawurlencode($val) . "&";
                }
            }
            return trim($data, "&");
        }
        return $args;
    }
}
