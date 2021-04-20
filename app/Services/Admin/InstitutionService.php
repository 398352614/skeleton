<?php
/**
 * @Author: h9471
 * @Created: 2020/1/4 17:00
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Model;

class InstitutionService extends BaseService
{
    public function __construct(Institution $institution)
    {
        parent::__construct($institution);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function indexOfEmployees(int $id)
    {
        return self::getInstance(EmployeeService::class)
            ->indexOfInstitution($id);
    }

    /**
     * 检查层数
     *
     * @param $parentId
     * @throws BusinessLogicException
     */
    public function checkDistance($parentId)
    {
        $distance = Institution::findOrFail($parentId)->getRoot()->distance;
        if ($distance > 3) {
            throw new BusinessLogicException('组织机构最高为3级');
        }
    }

    /**
     * 创建节点
     *
     * @param int $parentId
     * @param array $data
     * @return bool
     * @throws BusinessLogicException
     */
    public function createNode(int $parentId, array $data)
    {
        /*if ($parentId === 0) {
            $child = Institution::create([
                'name' => $data['name'],
                'phone' => $data['phone'] ?? '',
                'contacts' => $data['contacts'] ?? '',
                'country' => $data['country'] ?? '',
                'address' => $data['address'] ?? '',
                'parent' => $this->getCompanyRootId(),
            ]);

             return $this->getCompanyRoot()->addChild($child);
        }*/
        $this->checkDistance($parentId);
        $child = Institution::create([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? '',
            'contacts' => $data['contacts'] ?? '',
            'country' => $data['country'] ?? '',
            'address' => $data['address'] ?? '',
            'parent' => $parentId,
        ]);

        return $child->moveTo($parentId);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        return Institution::findOrFail($id);
    }

    /**
     * 更新节点信息
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateNode(int $id, array $data)
    {
        /** @var Institution $institution */
        $institution = Institution::findOrFail($id);

        return $institution->update([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? '',
            'contacts' => $data['contacts'] ?? '',
            'country' => $data['country'] ?? '',
            'address' => $data['address'] ?? '',
        ]);
    }

    /**
     * 获得树
     *
     * @return array
     */
    public function getTree(): array
    {
//        $info = $this->getCompanyService()->getInfo(['id' => auth()->user()->company_id], ['*'], false);
        /*  if(!$this->query->where('company_id','=',auth()->user()->company_id)->where('country','<>',null)->exists()){
              $this->createNode(0,[
                  'name'=>$info['name'],
                  'phone'=>$info['phone']??'',
                  'country'=> $info['id']??'',
                  'address'=>$info['address']??'',
                  'contacts'=>$info['contacts']??'',
                  'parent'=>0,
                    ]);
          }*/
        return Institution::getRoots()->first()->getTree()[0]['children'] ?? [];
    }

    /**
     * 获得子节点树
     *
     * @param int $id
     * @return array
     */
    public function getChildren(int $id): array
    {
        return Institution::findOrFail($id)->getTree();
    }

    /**
     * 移动到某一个节点下面
     *
     * @param int $id
     * @param int $parentId
     * @return bool
     */
    public function move(int $id, int $parentId): bool
    {
        /** @var Institution $institution */
        $institution = Institution::findOrFail($id);

        return $institution->moveTo($parentId);
    }

    /**
     * 删除节点
     *
     * @param int $id
     * @return bool
     * @throws BusinessLogicException
     * @throws \Exception
     */
    public function deleteNode(int $id): bool
    {
        if ($id === Institution::query()->where('company_id', auth()->user()->company_id)->where('parent', '<>', 0)->first()->toArray()['id']) {
            throw new BusinessLogicException('无法删除根组织');
        }
        /** @var Institution $institution */
        $institution = Institution::findOrFail($id);

        if ($this->hasChildren($institution)) {
            throw new BusinessLogicException('该节点存在子机构，请先删除子机构');
        }
        $info=$this->getEmployeeService()->getInfo(['institution_id'=>$id]);
        if(!empty($info)){
            throw new BusinessLogicException('请先删除该机构下的所有成员');
        }

        return $institution->delete();
    }

    /**
     * 删除节点和以及后代
     * @param int $id
     * @return bool
     * @throws BusinessLogicException
     */
    public function deleteNodeWithChildren(int $id): bool
    {
        /** @var Institution $institution */
        $institution = Institution::findOrFail($id);

        $institution->detachSelf();

        $isolated = Institution::getIsolated();

        $isolated->flatMap(function (Institution $institution) {
            return $institution->delete();
        });

        return true;
    }

    /**
     * 是否有孩子节点
     *
     * @param Institution $institution
     * @return bool
     */
    protected function hasChildren(Institution $institution): bool
    {
        return $institution->getChildren()->count() > 0;
    }

    /**
     * 创建公司根节点
     *
     * @return Model
     */
    protected function createCompanyRoot(): Model
    {
        $companyRoot = Institution::create([
            'name' => auth()->user()->company_id,
            'parent' => 0,
        ]);

        $companyRoot->makeRoot();

        return $companyRoot;
    }

    /**
     * 是否有根节点
     *
     * @return bool
     */
    protected function hasCompanyRoot(): bool
    {
        return Institution::getRoots()->first() !== null;
    }

    /**
     * 获得根节点
     *
     * @return Model
     */
    protected function getCompanyRoot(): Model
    {
        if ($this->hasCompanyRoot()) {
            return Institution::getRoots()->first();
        }

        return $this->createCompanyRoot();
    }

    /**
     * 获得根节点的ID
     *
     * @return int
     */
    protected function getCompanyRootId(): int
    {
        return $this->getCompanyRoot()->id;
    }
}
