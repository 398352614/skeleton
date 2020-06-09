<?php
/**
 * barcode trait
 * User: long
 * Date: 2020/5/29
 * Time: 14:16
 */

namespace App\Traits;


use Milon\Barcode\DNS1D;

trait BarcodeTrait
{
    public static function generateOne($content)
    {
        $barcode = DNS1D::setStorPath(config('barcode.store_path'))->getBarcodePNGPath($content, 'C128');
        return $barcode;
    }
}