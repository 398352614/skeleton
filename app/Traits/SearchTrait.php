<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait SearchTrait
{
    //todo q先进行正则匹配，再指定字段

    public static function buildQuery(Builder $query, array $conditions)
    {
        foreach ($conditions as $k => $v) {
            $type = '=';
            $value = $v;
            if (is_array($v) && $k != 'andQuery') {
                [$type, $value] = $v;
            }
            !is_array($value) ? $value = trim($value) : 1;
            //如果是like搜索，但是值为空，跳过
            if ($type === 'like' && $value === '') {
                continue;
            }
            //如果是like查询，但其中包含Mysql不能识别的%和_则加上转义符号
            if($type === 'like'){
                $value=str_replace('_','\_',$value);
                $value=str_replace('%','\%',$value);
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
            //not in
            if ($type === 'not in' && is_array($value)) {
                $query->whereNotIn($k, $value);
                continue;
            }
            //如果是between， 按时间过滤
            if ($type === 'between' && is_array($value)) {
                if (empty($value[0]) || empty($value[1])) continue;
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
                } else {
                    $query->where(function ($q) use ($k, $value) {
                        foreach (explode(',', $k) as $item) {
                            $q->orWhere($item, 'like', "%{$value}%");
                        }
                    });
                }
            } else { //普通类型
                if ($value === '')
                    continue;
                $query->where($k, $type, $type === 'like' ? "%{$value}%" : $value);
            }
        }
    }
}
