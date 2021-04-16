<?php

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\BaseResource;
use App\Models\BaseModel;
use App\Models\OrderNoRule;
use App\Traits\FactoryInstanceTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class BaseService
{
    use SearchTrait, FactoryInstanceTrait;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var Builder $query
     */
    public $query;

    /**
     * @var BaseModel|Builder
     */
    public $model;

    /**
     * 资源类
     * @var Resource $resource
     */
    protected $resource;

    /**
     * 资源类
     * @var Resource $infoResource
     */
    protected $infoResource;

    /**
     * @var array 过滤
     */
    public $filters = [];

    /**
     * @var array 过滤规则
     */
    public $filterRules = [];

    /**
     * @var array
     */
    public $orderBy = [];

    /**
     * @var int
     */
    public $per_page = 10;

    /**
     * @var array 表单数据
     */
    public $formData = [];

    public function __construct(Model $model, $resource = null, $infoResource = null)
    {
        if (empty($resource)) {
            $resource = $infoResource = BaseResource::class;
        }
        $this->model = $model;
        $this->query = $this->model::query();
        $this->resource = $resource;
        $this->infoResource = $infoResource;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
        $this->initOrderBy();
    }

    /**
     *
     */
    protected function initOrderBy()
    {
        $fillAble = $this->model->getFillable();

        $orderBy = $this->request->get('order_by');

        if (empty($orderBy)) {
            return;
        }

        $orderBy = explode(',', $orderBy);

        foreach ($orderBy as $item) {
            [$key, $sort] = explode('=', $item);

            if (!in_array($sort, ['asc', 'desc'])) {
                continue;
            }

            if (!in_array($key, $fillAble)) {
                continue;
            }

            $this->orderBy[$key] = $sort;
        }
    }

    /**
     * @return $this
     */
    public function setFilter()
    {
        if ($this->filters) {
            static::buildQuery($this->query, $this->filters);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function setFilterRules()
    {
        foreach ($this->filterRules as $k => $v) {
            if (Arr::has($this->formData, $v[1])) {
                //获取操作符
                $this->filters[$k][0] = $v[0];
                //获取值
                if (is_array($v[1])) {
                    $this->filters[$k][1] = [];
                    foreach ($v[1] as $v1) {
                        array_push($this->filters[$k][1], $this->formData[$v1]);
                    }
                } else {
                    $this->filters[$k][1] = $this->formData[$v[1]];
                }
            }
        }

        return true;
    }

    /**
     * @return $this
     */
    public function setOrderBy()
    {
        if ($this->orderBy) {
            foreach ($this->orderBy as $key => $value) {
                $this->query->orderBy($key, $value);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaginate()
    {
        $this->per_page = $this->request->input('per_page', 200);

        return $this->query->paginate($this->per_page);
    }

    /**
     * @return bool
     */
    protected function validatePaginate()
    {
        if ($this->request->input('per_page')) {
            $this->request->validate([
                'page' => 'integer',
                'per_page' => 'integer|in:10,20,50,100,200',
            ]);
        }

        return true;
    }

    /**
     * @return $this
     */
    protected function locked()
    {
        if ($this->model instanceof OrderNoRule) {
            $this->query->lockForUpdate();
        } else {
            $this->query->sharedLock();
        }

        return $this;
    }

    /**
     * 分页列表
     * @return Collection
     */
    public function getPageList()
    {
        return $this->resource::collection($this->setFilter()->setOrderBy()->getPaginate());
    }

    /**
     * 获取列表并锁定
     * @param  array  $where
     * @param  array  $selectFields
     * @param  bool  $isResource
     * @param  array  $groupFields
     * @param  array  $orderFields
     * @return array|Builder[]|Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getListLock(
        $where = [],
        $selectFields = ['*'],
        $isResource = true,
        $groupFields = [],
        $orderFields = []
    ) {
        return $this->locked()->getList($where, $selectFields, $isResource, $groupFields, $orderFields);
    }


    /**
     * 获取列表
     * @param $where
     * @param $selectFields
     * @param $isResource bool 是否生成资源类
     * @param $groupFields
     * @param $orderFields
     * @return Builder[]|Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getList(
        $where = [],
        $selectFields = ['*'],
        $isResource = true,
        $groupFields = [],
        $orderFields = []
    ) {
        if (!empty($where)) {
            SearchTrait::buildQuery($this->query, $where);
        }
        if (!empty($groupFields)) {
            $this->query->groupBy($groupFields);
        }
        if (!empty($orderFields)) {
            $keyArr = array_keys($orderFields);
            foreach ($keyArr as $key) {
                $this->query->orderBy($key, $orderFields[$key]);
            }
        }
        if ($isResource) {
            $data = $this->resource::collection($this->query->get($selectFields));
        } else {
            $data = $this->query->get($selectFields);
        }
        $this->query = $this->model::query();

        return $data;
    }


    /**
     * 获取详情,并加锁
     * @param $where
     * @param  array  $selectFields
     * @param  bool  $isResource
     * @param  array  $orderFields
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getInfoLock($where, $selectFields = ['*'], $isResource = true, $orderFields = [])
    {
        $query = $this->query;
        SearchTrait::buildQuery($query, $where);
        if (!empty($orderFields)) {
            $keyArr = array_keys($orderFields);
            foreach ($keyArr as $key) {
                $query->orderBy($key, $orderFields[$key]);
            }
        }
        $data = $query->first($selectFields);
        unset($query);
        if (empty($data)) {
            return null;
        }

        return $this->locked()->getInfo($where, $selectFields, $isResource, $orderFields);
    }


    /**
     * 获取详情
     * @param $where
     * @param $selectFields
     * @param  bool  $isResource
     * @param  array  $orderFields
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getInfo($where, $selectFields = ['*'], $isResource = true, $orderFields = [])
    {
        SearchTrait::buildQuery($this->query, $where);
        if (!empty($orderFields)) {
            $keyArr = array_keys($orderFields);
            foreach ($keyArr as $key) {
                $this->query->orderBy($key, $orderFields[$key]);
            }
        }
        $data = $this->query->first($selectFields);
        if (empty($data)) {
            $this->query = $this->model::query();

            return [];
        };
        if ($isResource) {
            $data = $this->infoResource::make($data)->toArray(request());
        }
        $this->query = $this->model::query();

        return $data;
    }

    public function create($data)
    {
        $this->query = $this->model::query();

        return $this->query->create(Arr::only($data, $this->model->getFillable()));
    }

    public function insertAll($data)
    {
        $fields = $this->model->getFillable();
        foreach ($data as $key => $item) {
            $data[$key] = Arr::only($item, $fields);
        }

        return $this->model->insertAll($data);
    }


    public function insertGetId($data)
    {
        $this->query = $this->model::query();
        if (in_array('company_id', $this->model->getFillable()) && !isset($data['company_id'])) {
            $data['company_id'] = auth()->user()->company_id;
        }
        $data['created_at'] = $data['updated_at'] = now();

        return $this->query->insertGetId($data);
    }

    /**
     * 修改
     * @param $where
     * @param $data
     * @return int
     */
    public function update($where, $data)
    {
        $this->query = $this->model::query();
        SearchTrait::buildQuery($this->query, $where);
        $rowCount = $this->query->update(Arr::only($data, $this->model->getFillable()));
        $this->query = $this->model::query();

        return $rowCount;
    }

    /**
     * 通过主键ID更新
     * @param $id
     * @param $data
     * @return bool|int
     */
    public function updateById($id, $data)
    {
        $this->query = $this->model::query();
        $query = $this->query->findOrFail($id);
        $rowCount = $query->update(Arr::only($data,
            Arr::except($this->model->getFillable(), ['company_id', 'order_no', 'batch_no', 'tour_no'])));
        $this->query = $this->model::query();

        return $rowCount;
    }

    /**
     * 通过ID,指定字段自增
     * @param $id
     * @param $field
     * @param $data
     * @return int
     */
    public function incrementById($id, $field, $data)
    {
        $this->query = $this->model::query();
        $query = $this->query->findOrFail($id);
        $rowCount = $query->increment($field, $data[$field], Arr::except($data, $data[$field]));
        $this->query = $this->model::query();

        return $rowCount;
    }

    /**
     * 指定字段自增
     * @param $where
     * @param $field
     * @param $data
     * @return int
     */
    public function increment($where, $field, $data)
    {
        $this->query = $this->model::query();
        SearchTrait::buildQuery($this->query, $where);
        $rowCount = $this->query->increment($field, $data[$field], Arr::except($data, $data[$field]));
        $this->query = $this->model::query();

        return $rowCount;
    }


    /**
     * 启用/禁用
     * @param $id
     * @param $data
     * @return
     * @throws BusinessLogicException
     */
    public function status($id, $data)
    {
        $rowCount = self::updateById($id, ['status' => $data['status']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，清重新操作');
        }

        return 'true';
    }


    public function delete($where)
    {
        $this->query = $this->model::query();
        SearchTrait::buildQuery($this->query, $where);
        $rowCount = $this->query->delete();
        $this->query = $this->model::query();

        return $rowCount;
    }


    /**
     * 批量删除
     * @param $idList
     * @return mixed
     */
    public function deleteAllByIdList($idList)
    {
        $idList = is_array($idList) ? $idList : explode(',', $idList);
        $idList = array_filter($idList, function ($value) {
            return is_numeric($value);
        });
        if (empty($idList)) {
            return false;
        }
        $rowCount = $this->delete(['id' => ['in', $idList]]);
        $this->query = $this->model::query();

        return $rowCount;
    }


    public function count($where = [])
    {
        if (!empty($where)) {
            SearchTrait::buildQuery($this->query, $where);
        }
        $count = $this->query->count();
        $this->query = $this->model::query();

        return empty($count) ? 0 : $count;
    }

    public function sum($field, $where = [])
    {
        if (!empty($where)) {
            SearchTrait::buildQuery($this->query, $where);
        }
        $sum = $this->query->sum($field);
        $this->query = $this->model::query();

        return !empty($sum) ? $sum : 0;
    }

    /**
     * 根据状态获取信息
     * @param $where
     * @param $isToArray
     * @param $status
     * @param $isLock
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getInfoOfStatus($where, $isToArray = true, $status = 1, $isLock = true)
    {
        $info = ($isLock === true) ? $this->getInfoLock($where, ['*'], false) : $this->getInfo($where, ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (!in_array(intval($info['status']), Arr::wrap($status))) {
            throw new BusinessLogicException('当前状态是[:status_name]，不能操作', 1000, ['status_name' => $info['status_name']]);
        }

        return $isToArray ? $info->toArray() : $info;
    }

    /**
     * @param  array  $where
     * @param  array  $data
     * @return Builder|Model
     */
    public function updateOrCreate(array $where, array $data)
    {
        return $this->query->updateOrCreate($where, $data);
    }

    /**
     * @param $name
     * @param $arguments
     * @return null|mixed
     * @throws BusinessLogicException
     */
    public function __call($name, $arguments)
    {
        if (preg_match('/^(get)(\w+)(Service)$/', $name)) {
            $className = substr($name, 3);
            $className = __NAMESPACE__ . '\\' . $className;
            if (!class_exists($className)) {
                $className = 'App\Service\\' . $className;
                if (!class_exists($className)) {
                    throw new BusinessLogicException($className . '类不存在');
                }
            }
            return FactoryInstanceTrait::getInstance($className, $arguments);
        }
        throw new BusinessLogicException('方法不存在');
    }
}
