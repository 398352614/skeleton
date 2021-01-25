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
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Facades\Storage;

class PrintTemplateService extends BaseService
{
    public function __construct(PrintTemplate $model, $resource = null, $infoResource = null)
    {
        parent::__construct($model, $resource, $infoResource);
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
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }
}
