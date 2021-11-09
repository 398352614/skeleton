<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\ArticleResource;
use App\Models\Article;
use App\Services\BaseConstService;
use App\Traits\UserTrait;


class ArticleService extends BaseService
{
    public function __construct(Article $address)
    {
        parent::__construct($address, ArticleResource::class);
    }

    public $filterRules = [

    ];

    public $orderBy = [
        'updated_at' => 'desc',
    ];

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }


    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['operator_id'] = auth()->user()->id;
        $params['operator_name'] = UserTrait::get(auth()->user()->id,BaseConstService::USER_ADMIN)['name'];
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败，请重新操作');
        }
    }

    /**
     * 通过ID 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $data['operator_id'] = auth()->user()->id;
        $data['operator_name'] = UserTrait::get(auth()->user()->id,BaseConstService::USER_ADMIN)['name'];
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
    }

    /**
     * 排序
     * @param $data
     */
    public function updateSort($data)
    {
        $ids = $data['id_list'];
        foreach ($ids as $k => $v) {
            parent::updateById($v, ['sort_id' => $k]);
        }
    }

    public function index()
    {
        $data= parent::getPageList();
        return $data;
    }

}
