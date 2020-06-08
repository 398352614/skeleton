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
        $d = new DNS1D();
        $d->setStorPath(storage_path('app/public/admin/barcode/'));
        $barcode = $d->getBarcodePNGPath($content, 'C128');
        return $barcode;
    }
}