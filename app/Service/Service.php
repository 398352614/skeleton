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
namespace App\Service;

use App\Exception\BusinessException;
use App\Request\Request;
use App\Resource\Resource;
use App\Trait\SearchTrait;
use Carbon\Carbon;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Arr;
use Psr\Container\ContainerInterface;

class Service
{
    use SearchTrait;

    /**
     * @Inject
     */
    protected RequestInterface $request;

    protected Model $model;

    protected array $exceptMethods = [];

    protected string $resource;

    protected Builder $query;

    protected array $formData;

    protected array $filters = [];

    protected array $filterRules = [];

    protected array $orderBy;

    protected ?Request $validation;

    /**
     * @inject
     */
    protected ContainerInterface $container;

    /**
     * Service constructor.
     */
    public function __construct(
        Model $model,
        mixed $resource = null,
        Request $request = null
    ) {
        if (empty($resource)) {
            $this->resource = Resource::class;
        } else {
            $this->resource = $resource::class;
        }
        $this->model = $model;
        $this->query = $this->model::query();
        $this->setFilterRules();
        $this->formData = $this->request->all();
//        $this->initOrderBy();
//        $this->validate($request);
    }

    /**
     * @param $method
     * @param $arguments
     * @throws \Exception
     * @return false|mixed
     */
    public function __call($method, $arguments)
    {
        if (in_array($method, $this->exceptMethods)) {
            return call_user_func_array([$this, $method], ! empty($arguments) ? $arguments : []);
        }
        // show/get/query/find 表示只读事物
        $pattern = '/^(show\w*)$|^(get\w*)$|^(select\w*)$|^(query\w*)$|^(find\w*)$|^(export\w*)|^(index\w*)$/';
        preg_match($pattern, $method, $match);
        // 匹配上了，就直接执行只读方法
        if (is_array($match) && count($match)) {
            $return = call_user_func_array([$this, $method], ! empty($arguments) ? $arguments : []);
        } else {
            // 没有匹配上，则加上事物执行
            $return = $this->transaction($method, ! empty($arguments) ? $arguments : []);
        }
        return $return;
    }

    public function index(): mixed
    {
        return $this->getList(page: true);
    }

    /**
     * @param $data
     * @param bool|string $returnId
     */
    public function store(
        $data,
        bool $returnId = false
    ): Model|array|int|Builder {
        $this->query = $this->model::query();
        if ($returnId == true) {
            $data['created_at'] = $data['updated_at'] = Carbon::now();
            return $this->query->insertGetId($data);
        }
        return $this->model->create(Arr::only($data, $this->model->getFillable()));
    }

    public function storeByList($data): bool
    {
        $fields = $this->model->getFillable();
        foreach ($data as $key => $item) {
            $item['created_at'] = $item['updated_at'] = Carbon::now();
            $data[$key] = Arr::only($item, $fields);
        }
        return $this->model->newQuery()->insert($data);
    }

    public function show(
        $where = [],
        $only = ['*'],
        $resource = false,
        $orderBy = ['id' => 'desc']
    ) {
        $this->byId($where);
        SearchTrait::buildQuery($this->query, $where);
        if (! empty($orderBy)) {
            $keyArr = array_keys($orderBy);
            foreach ($keyArr as $key) {
                $this->query->orderBy($key, $orderBy[$key]);
            }
        }
        $data = $this->query->first($only);
        if (empty($data)) {
            $this->query = $this->model::query();

            return [];
        }
        if ($resource) {
            $data = new $this->resource($data);
        }
        $this->query = $this->model::query();

        return $data;
    }

    public function transaction($method, array $param)
    {
        try {
            // 开启事物
            Db::beginTransaction();
            if (! method_exists($this, $method)) {
                throw new BusinessException($method . '方法未定义', );
            }
            $return = call_user_func_array([$this, $method], $param);
            // 提交事物
            DB::commit();
        } catch (BusinessException $e) {
            // 回滚事物
            DB::rollBack();
            throw new BusinessException($e->getMessage(), $e->getCode(), $e->replace, $e->data);
        } catch (\Exception $e) {
            // 回滚事物
            DB::rollBack();
            throw $e;
        }
        return $return;
    }

    public function destroy($where)
    {
        $this->byId($where);
        $this->query = $this->model::query();
        SearchTrait::buildQuery($this->query, $where);
        $rowCount = $this->query->delete();
        $this->query = $this->model::query();
        return $rowCount;
    }

    public function edit($where, $data): int
    {
        $this->byId($where);
        $this->query = $this->model::query();
        SearchTrait::buildQuery($this->query, $where);
        $rowCount = $this->query->update(Arr::only(
            $data,
            Arr::except($this->model->getFillable(), 'created_at')
        ));
        $this->query = $this->model::query();
        return $rowCount;
    }

    public function byId(&$where)
    {
        if (! empty($where) && ! is_array($where)) {
            $where = ['id' => $where];
        }
    }
}
