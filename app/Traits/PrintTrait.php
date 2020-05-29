<?php
/**
 * 打印 trait
 * User: long
 * Date: 2020/5/29
 * Time: 10:36
 */

namespace App\Traits;


use App\Exceptions\BusinessLogicException;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Barryvdh\Snappy\PdfFaker;
use Illuminate\Support\Facades\Storage;

trait PrintTrait
{

    private static function getDir($dir)
    {
        return $dir . DIRECTORY_SEPARATOR . auth()->user()->company_id;
    }

    /**
     * 打印
     * @param $data
     * @param $view
     * @param $dir
     * @param $fileName
     * @return mixed
     * @throws BusinessLogicException
     */
    public static function tPrint($data, $view, $dir, $fileName)
    {
        $dir = self::getDir($dir);
        try {
            $newFilePath = storage_path('app/public/pdf') . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $fileName;
            /** @var PdfFaker $snappyPdf */
            $snappyPdf = SnappyPdf::loadView($view, ['data' => $data]);
            $snappyPdf->save($newFilePath, true);
            unset($snappyPdf);
        } catch (\Exception $ex) {
            throw new BusinessLogicException('打印失败');
        }
        $url = Storage::disk('public_pdf')->url(DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $fileName);
        return $url;
    }
}