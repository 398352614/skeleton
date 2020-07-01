<?php

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\OrderInfoResource;
use App\Http\Resources\OrderResource;
use App\Models\BaseModel;
use App\Traits\FactoryInstanceTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Resources\Json\JsonResource;

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
     * @var BaseModel
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

    public $orderBy = [];

    public $per_page = 10;

    /**
     * @var array 表单数据
     */
    public $formData = [];

    public function __construct(Model $model, $resource = null, $infoResource = null)
    {
        $this->model = $model;
        $this->query = $this->model::query();
        $this->resource = $resource;
        $this->infoResource = $infoResource;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }


    /**
     * @return $this
     */
    public function setFilter()
    {
        if ($this->filters)
            static::buildQuery($this->query, $this->filters);
        return $this;
    }

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
        if ($this->validatePaginate())
            $this->per_page = $this->request->input('per_page');
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


    protected function locked()
    {
        $this->query->sharedLock();
        return $this;
    }

    /**
     * 分页列表
     * @return mixed
     */
    public function getPageList()
    {
        return $this->resource::collection($this->setFilter()->setOrderBy()->getPaginate());
    }


    /**
     * 获取列表
     * @param $where
     * @param $selectFields
     * @param $isResource bool 是否生成资源类
     * @param $groupFields
     * @param $orderFields
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getList($where = [], $selectFields = ['*'], $isResource = true, $groupFields = [], $orderFields = [])
    {
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
     * @param array $selectFields
     * @param bool $isResource
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getInfoLock($where, $selectFields = ['*'], $isResource = true)
    {
        $query = $this->query;
        SearchTrait::buildQuery($query, $where);
        $data = $query->first($selectFields);
        if (empty($data)) {
            return null;
        }
        unset($query);
        return $this->locked()->getInfo($where, $selectFields, $isResource);
    }


    /**
     * 获取详情
     * @param $where
     * @param $selectFields
     * @param bool $isResource
     * @param array $orderFields
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getInfo($where, $selectFields = ['*'], $isResource = true, $orderFields = [])
    {
        SearchTrait::buildQuery($this->query, $where);
        $data = $this->query->first($selectFields);
        if (empty($data)) {
            $this->query = $this->model::query();
            return [];
        };
        if ($isResource) {
            $data = $this->infoResource::make($data)->toArray(request());
        }
        if (!empty($orderFields)) {
            $keyArr = array_keys($orderFields);
            foreach ($keyArr as $key) {
                $this->query->orderBy($key, $orderFields[$key]);
            }
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
        $rowCount = $query->update(Arr::only($data, Arr::except($this->model->getFillable(), ['company_id', 'order_no', 'batch_no', 'tour_no'])));
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
     * @throws BusinessLogicException
     */
    public function status($id, $data)
    {
        $rowCount = self::updateById($id, ['status' => $data['status']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，清重新操作');
        }
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
            throw new BusinessLogicException('当前状态下不能操作');
        }
        return $isToArray ? $info->toArray() : $info;
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
        };
        throw new BusinessLogicException('方法不存在');
    }
}
