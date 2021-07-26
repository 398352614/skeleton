<?php


namespace App\Console\Commands;


use Exception;
use Illuminate\Console\Command;

class EoLinkerToApiDoc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-doc {--file= : file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'api-doc';

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
        $apiDoc = '<?php
        /**
 * @apiDefine auth
 * @apiHeader {string} language 语言cn-中文en-英文。
 * @apiHeader {string} Authorization [必填]令牌，以bearer加空格加令牌为格式。
 * @apiHeaderExample {json} Header-Example:
 * {
 *       "language": "en"
 *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTkxMjU4NDAzLCJleHAiOjE1OTI0NjgwMDMsIm5iZiI6MTU5MTI1ODQwMywianRpIjoidGV2MG1hQlM1T0lDVm5JRCIsInN1YiI6NjEsInBydiI6IjMyOTYzYTYwNmMyZjE3MWYxYzE0MzMxZTc2OTc2NmNkNTkxMmVkMTUiLCJyb2xlIjoiZW1wbG95ZWUifQ.8NVjy4OyITV3Cu3k3m_BwNc5Yqf2Ld-ibRQ7r9Q82kw"
 *     }
 */
 ';
        $eoLinker = file_get_contents($this->option('file'));
        $eoLinker = json_decode($eoLinker, true);
        foreach ($eoLinker['apiGroupList'][0]['apiGroupChildList'] as $k => $v) {
            $group = $v;
            $apiDoc .= $this->formGroup($k, $group);
        }
        foreach ($eoLinker['apiGroupList'][0]['apiGroupChildList'] as $k => $v) {
            $group = $v;
            foreach ($group['apiList'] as $x => $api) {
                $apiDoc .= $this->form($k, $api);
            }
        }
        file_put_contents(base_path('public/api/routes/admin/admin.php'), $apiDoc);
        return $apiDoc;
    }

    public function form($key, $api)
    {
        if ($key < 10) {
            $key = '0' . $key;
        }
        $array = ['post', 'get', 'put', 'delete'];
        $params = '';
        foreach ($api['requestInfo'] as $k => $v) {
            $params .= '* @apiParam {string} ' . $v['paramKey'] . ' ' . $v['paramName'] . "\n";
        }
        $result = '';
        foreach ($api['resultInfo'] as $k => $v) {
            $api['resultInfo'][$k]['paramKey'] = str_replace('>>', '.', $v['paramKey']);
            $result .= '* @apiSuccess {string} ' . $api['resultInfo'][$k]['paramKey'] . ' ' . $v['paramName'] . "\n";
        }
        $resultJson = $api['baseInfo']['apiSuccessMock'];
        if (!empty($resultJson)) {
            $resultJson = "* @apiSuccessExample {json} Success-Response:
" . $resultJson;
        }

        $data = sprintf(
            "/**
* @api {%s} %s %s
* @apiName %s
* @apiGroup %s
* @apiVersion 1.0.0
* @apiUse auth
{$params}
{$result}
{$resultJson}
*/

",
            $array[$api['baseInfo']['apiRequestType']],
            $api['baseInfo']['apiURI'],
            $api['baseInfo']['apiName'],
            $api['baseInfo']['apiName'],
            $key
        );
        return $data;
    }

    public function formGroup($k, $group)
    {
        if ($k < 10) {
            $k = '0' . $k;
        }
        $groupName = $group['groupName'];
        $data = sprintf("/**
* @apiDefine %s %s
*/
",
            $k, $groupName);
        return $data;
    }


}
