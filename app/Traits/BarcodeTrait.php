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
        $path = \Illuminate\Support\Facades\Storage::disk('admin_barcode_public')->getAdapter()->getPathPrefix();
        $barcode = DNS1D::setStorPath($path)->getBarcodePNGPath($content, 'C128');
        return $barcode;
    }
}