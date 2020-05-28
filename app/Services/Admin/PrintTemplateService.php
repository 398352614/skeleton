<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/28
 * Time: 10:25
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Models\PrintTemplate;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;

class PrintTemplateService extends BaseService
{
    public function __construct(PrintTemplate $model, $resource = null, $infoResource = null)
    {
        parent::__construct($model, $resource, $infoResource);
    }

    public function show()
    {
        $info = parent::getInfo(['company_id' => auth()->user()->company_id], ['id', 'company_id', 'type'], false);
        return $info;
    }

    /**
     * 创建或修改
     * @param $params
     * @throws BusinessLogicException
     */
    public function createOrUpdate($params)
    {
        $rowCount = $this->query->updateOrCreate(['company_id' => auth()->user()->company_id], [
            'type' => $params['type']
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }
}