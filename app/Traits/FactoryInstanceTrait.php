<?php
/**
 * 工厂单例生产类
 * User: long
 * Date: 2019/7/25
 * Time: 14:57
 */

namespace App\Traits;

/**
 * Trait FactoryInstanceTrait
 * @package App
 * @method static Route
 */
trait FactoryInstanceTrait
{
    public static function getInstance($className, $parameters = [])
    {
        app()->singleton($className);
        return app()->make($className, $parameters);
    }
}