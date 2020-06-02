<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/18
 * Time: 17:46
 */

namespace App\Services;


use App\Traits\ExportTrait;
use Illuminate\Database\Eloquent\Model;

class ExportService extends BaseService
{
    use ExportTrait;

    public $header = [];

    public $dataList = [];

    public function __construct(Model $model, $resource = null, $infoResource = null)
    {
        parent::__construct($model, $resource, $infoResource);
    }

    /**
     * @param string $name
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function export($name, $dir)
    {
        $this->dataList = $this->getDataList();

        return $this->excelExport($name, $this->header, $this->dataList, $dir);
    }


    public function getDataList()
    {
        return [];
    }
}