<?php

namespace App;

use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;

class Log
{
    public static function get(string $name = 'app')
    {
        return ApplicationContext::getContainer()->get(LoggerFactory::class)->get($name);
    }

    public static function error($message, $data, string $name = 'hyperf', string $group = 'default')
    {
        return ApplicationContext::getContainer()->get(LoggerFactory::class)->make($name, $group)->error($message, $data);
    }
}
