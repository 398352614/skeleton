<?php
/**
 * 公司微信推送
 * User: long
 * Date: 2020/9/4
 * Time: 14:13
 */

namespace App\Services;

use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;

class MessageService
{
    /**
     * 公司微信推送
     * @param string $body
     */
    public function reportToWechat($body = '')
    {
        if (!config('tms.wechat_push')) {
            return;
        }
        $app = app('wechat.work');
        if (empty($app)) {
            return;
        }
        $requestUri = request()->url();
        switch (config('app.env')) {
            case 'dev':
                $env = '开发服';
                break;
            case 'test':
                $env = '测试服';
                break;
            case 'production':
                $env = '正式服';
                break;
            default:
                $env = '未知服' . config('app.env');
        }
        $items = [
            new NewsItem([
                'title' => $env . '报错,接口：' . $requestUri,
                'description' => $body
            ])
        ];
        $message = new News($items);
        $app->messenger->send($message);
    }
}
