<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/28
 * Time: 10:25
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Models\OrderTemplate;
use App\Services\BaseConstService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Facades\Storage;


class OrderTemplateService extends BaseService
{
    public function __construct(OrderTemplate $model, $resource = null, $infoResource = null)
    {
        parent::__construct($model, $resource, $infoResource);
    }


    /**
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $row = parent::updateById($id, $data);
        if ($row == false) {
            throw new BusinessLogicException('修改失败');
        }
    }

    /**
     * 初始化
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $data = [];
//        $templateList = array_create_index(ConstTranslateTrait::formatList(ConstTranslateTrait::$printTemplateList), 'id');
//        $disk = Storage::disk('admin_print_template_public');
//        $fileList = $disk->allFiles();
//        foreach ($fileList as $file) {
//            $fileName = explode('.', $file)[0];
//            if (!empty($templateList[$fileName])) {
//                $templateList[$fileName]['url'] = $disk->url($file);
//            }
//        }
//        $data['template_list'] = array_values($templateList);
        $data = parent::getPageList();
        return $data;
    }

    /**
     * 修改默认模板
     * @param $id
     * @throws BusinessLogicException
     */
    public function changeDefault($id)
    {
        $row = parent::updateById($id, ['is_default' => BaseConstService::ORDER_TEMPLATE_IS_DEFAULT_1]);
        if ($row == false) {
            throw new BusinessLogicException('修改失败');
        }
        $row = parent::update(['id' => ['<>', $id]], ['is_default' => BaseConstService::ORDER_TEMPLATE_IS_DEFAULT_2]);
        if ($row == false) {
            throw new BusinessLogicException('修改失败');
        }
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }

}
