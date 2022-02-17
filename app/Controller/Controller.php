<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Exception\BusinessException;
use App\Service\Service;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;

class Controller extends AbstractController
{
    /**
     * Controller constructor.
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * 列表查询.
     */
    public function index()
    {
        return $this->service->index();
    }

    /**
     * 查看详情.
     * @param $id
     * @return array|Builder|mixed|Model|object
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 新增.
     */
    public function store(): Model|array|int|Builder
    {
        return $this->service->store($this->request->all(), true);
    }

    /**
     * 修改.
     * @param $id
     */
    public function edit($id)
    {
        if (! $this->service->edit($id, $this->request->all())) {
            throw new BusinessException('修改失败');
        }
    }

    /**
     * 删除
     * @param $id
     */
    public function destroy($id)
    {
        if (! $this->service->destroy($id)) {
            throw new BusinessException('删除失败');
        }
    }
}
