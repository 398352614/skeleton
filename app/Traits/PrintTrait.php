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
use Illuminate\Support\Facades\View;

trait PrintTrait
{

    private static function getDir($dir)
    {
        return $dir . DIRECTORY_SEPARATOR . auth()->user()->company_id;
    }

    private static function getFileName()
    {
        return md5(auth()->user()->company_id . time()) . '.pdf';
    }

    /**
     * 打印
     * @param $dataList
     * @param $view
     * @param $dir
     * @param null $fileName
     * @return mixed
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public static function tPrintAll($dataList, $view, $dir, $fileName = null)
    {
        empty($fileName) && $fileName = self::getFileName();
        data_set($dataList, '*.currency_unit', __(CompanyTrait::getCompany()['currency_unit']));
        $dir = self::getDir($dir);
        try {
            $newFilePath = storage_path('app/public/pdf') . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $fileName;
            /** @var PdfFaker $snappyPdf */
            $html = '';
            foreach ($dataList as $data) {
                $html .= view($view, ['data' => $data])->render();
            }
            $snappyPdf = SnappyPdf::loadHTML($html);
            $snappyPdf->save($newFilePath, true);
            unset($snappyPdf);
        } catch (\Exception $ex) {
            throw new BusinessLogicException($ex->getMessage());
        }
        $url = Storage::disk('public_pdf')->url(DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $fileName);
        return $url;
    }
}
