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
namespace App\Trait;

use App\Exception\BusinessException;
use Carbon\Carbon;
use Hyperf\Database\Model\Builder;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Paginator\Paginator;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Context;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

trait SearchTrait
{
    /**
     * @Inject
     */
    protected ValidatorFactoryInterface $validationFactory;

    //todo q先进行正则匹配，再指定字段

    public static function buildQuery(Builder $query, array $conditions)
    {
        foreach ($conditions as $k => $v) {
            $type = '=';
            $value = $v;
            if (is_array($v) && $k != 'andQuery') {
                [$type, $value] = $v;
            }
            ! is_array($value) ? $value = trim($value) : 1;
            //如果是like搜索，但是值为空，跳过
            if ($type === 'like' && $value === '') {
                continue;
            }
            //如果是like查询，但其中包含Mysql不能识别的%和_则加上转义符号
            if ($type === 'like') {
                $value = str_replace('_', '\_', $value);
                $value = str_replace('%', '\%', $value);
            }
            //in
            if ($type === 'in' && is_array($value)) {
                $query->whereIn($k, $value);
                continue;
            }
            if ($type === '<>') {
                $query->where($k, $type, $value);
                continue;
            }
            if ($type === 'all') {
                $query->whereRaw("IFNULL({$k},0) <> -1");
                continue;
            }
            //not in
            if ($type === 'not in' && is_array($value)) {
                $query->whereNotIn($k, $value);
                continue;
            }
            //如果是between， 按时间过滤
            if ($type === 'between' && is_array($value)) {
                if (empty($value[0]) || empty($value[1])) {
                    continue;
                }
                //关联表
                if (strpos($k, ':')) {
                    $k = explode(':', $k);
                    $query->whereHas($k[0], function ($query) use ($k, $value) {
                        if (strpos($value[0], '-') && strpos($value[1], '-')) {
                            $query->whereBetween($k[1], [
                                Carbon::parse($value[0])->startOfDay(),
                                Carbon::parse($value[1])->endOfDay(),
                            ]);
                        } else {
                            $query->whereBetween($k[1], [$value[0], $value[1]]);
                        }
                    });
                    continue;
                }

                //主表过滤
                if (strpos($value[0], '-') && strpos($value[1], '-')) {
                    $query->whereBetween($k, [
                        Carbon::parse($value[0])->startOfDay(),
                        Carbon::parse($value[1])->endOfDay(),
                    ]);
                } else {
                    $query->whereBetween($k, [$value[0], $value[1]]);
                }
                //如果是多个字段联合搜索
            } elseif (strpos($k, ',')) {
                if (strpos($k, ':')) {
                    $k = explode(':', $k);
                    $query->whereHas($k[0], function ($q) use ($k, $value) {
                        $q->where(function ($query) use ($k, $value) {
                            foreach (explode(',', $k[1]) as $item) {
                                $query->orWhere($item, 'like', "%{$value}%");
                            }
                        });
                    });
                    continue;
                }
                $query->where(function ($q) use ($k, $value) {
                    foreach (explode(',', $k) as $item) {
                        $q->orWhere($item, 'like', "%{$value}%");
                    }
                });
            } else { //普通类型
                if ($value === '') {
                    continue;
                }
                $query->where($k, $type, $type === 'like' ? "%{$value}%" : $value);
            }
        }
    }

    /**
     * 设置排序条件.
     * @return $this
     */
    public function setOrderBy()
    {
        if (! empty($this->orderBy)) {
            foreach ($this->orderBy as $key => $value) {
                $this->query->orderBy($key, $value);
            }
        }

        return $this;
    }

    /**
     * 设置筛选条件.
     */
    public function setFilterRules(): bool
    {
        foreach ($this->filterRules as $k => $v) {
            if (Arr::has(Context::get('data'), $v[1])) {
                //获取操作符
                $this->filters[$k][0] = $v[0];
                //获取值
                if (is_array($v[1])) {
                    $this->filters[$k][1] = [];
                    foreach ($v[1] as $v1) {
                        array_push($this->filters[$k][1], Context::get('data')[$v1]);
                    }
                } else {
                    $this->filters[$k][1] = Context::get('data')[$v[1]];
                }
            }
        }

        return true;
    }

    /**
     * @return $this
     */
    public function setFilter(): static
    {
        if ($this->filters) {
            static::buildQuery($this->query, $this->filters);
        }
        return $this;
    }

    public function getPaginate($data): Paginator
    {
        $this->validatePaginate();
        return new Paginator($data, $this->formData['per_page'] ?? 200, $this->formData['per_page'] ?? 1);
    }

//    public function getPageList()
//    {
//        if (! empty(Context::get('data')) && array_key_exists('per_page', Context::get('data')) && Context::get('data')['per_page'] == 0) {
//            return $this->resource::collection($this->setFilter()->setOrderBy()->getList());
//        }
//        return $this->resource::collection($this->setFilter()->setOrderBy()->getPaginate());
//    }

    public function getList(
        $where = [],
        $only = ['*'],
        $resource = true,
        $groupBy = [],
        $orderBy = [],
        $page = false
    ): Paginator {
        if (! empty($where)) {
            SearchTrait::buildQuery($this->query, $where);
        } else {
            $this->setFilter();
        }
        if (! empty($groupBy)) {
            $this->query->groupBy($groupBy);
        }
        if (! empty($orderBy)) {
            $keyArr = array_keys($orderBy);
            foreach ($keyArr as $key) {
                $this->query->orderBy($key, $orderBy[$key]);
            }
        } else {
            $this->setOrderBy();
        }
        if ($page) {
            $data = $this->getPaginate($this->query->get($only));
        } elseif ($resource) {
            $data = $this->resource::collection($this->query->get($only));
        } else {
            $data = $this->query->get($only);
        }
        $this->query = $this->model::query();

        return $data;
    }

    /**
     * 自动将请求参数里的order_by作为排序依据，order_by=id,asc.
     */
    protected function initOrderBy()
    {
        $fillAble = $this->model->getFillable();

        $orderBy = $this->request->get();

        if (empty($orderBy)) {
            return;
        }

        $orderBy = explode(',', $orderBy);

        foreach ($orderBy as $item) {
            [$key, $sort] = explode('=', $item);

            if (! in_array($sort, ['asc', 'desc'])) {
                continue;
            }

            if (! in_array($key, $fillAble)) {
                continue;
            }

            $this->orderBy[$key] = $sort;
        }
    }

    protected function validatePaginate()
    {
        $validator = $this->validationFactory->make(
            $this->formData,
            [
                'page' => 'integer',
                'per_page' => 'integer|in:0,5,10,20,40,50,100,200,1000',
            ],
        );
        if ($validator->fails()) {
            $messageList = Arr::flatten($validator->errors()->getMessages());
            throw new BusinessException(implode(';', $messageList), 3001);
        }
    }
}
