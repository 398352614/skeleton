<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/28
 * Time: 10:25
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\OrderTemplate;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Facades\Storage;


class OrderTemplateService extends BaseService
{
    public function __construct(OrderTemplate $model, $resource = null, $infoResource = null)
    {
        parent::__construct($model, $resource, $infoResource);
    }

    public function show()
    {
        $info = parent::getInfo(['company_id' => auth()->user()->company_id], ['*'], false);
        return $info;
    }

    /**
     * 创建或修改
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function updateById($id, $params)
    {
        $rowCount = $this->updateById($id, $params);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * 初始化
     * @return array
     */
    public function init()
    {
        $data = [];
        $templateList = array_create_index(ConstTranslateTrait::formatList(ConstTranslateTrait::$printTemplateList), 'id');
        $disk = Storage::disk('admin_print_template_public');
        $fileList = $disk->allFiles();
        foreach ($fileList as $file) {
            $fileName = explode('.', $file)[0];
            if (!empty($templateList[$fileName])) {
                $templateList[$fileName]['url'] = $disk->url($file);
            }
        }
        $data['template_list'] = array_values($templateList);
        return $data;
    }
}
