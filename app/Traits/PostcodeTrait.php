<?php
/**
 * 国家 trait
 * User: long
 * Date: 2020/3/9
 * Time: 14:22
 */

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

trait PostcodeTrait
{
    /**
     * 通过省市区,获取邮编
     * @param $params
     * @return mixed|string
     */
    public static function getPostcode($params)
    {
        self::initPostcodeList();
        $postcodeList = json_decode(Cache::get($params['province'] . $params['city']));
        return ['postcode' => collect($postcodeList)->toArray()[$params['district']] ?? ''];
    }

    /**
     *
     */
    public static function initPostcodeList()
    {
        if (!Cache::has('河北省石家庄市')) {
            $data = [];
            $postcodeList = json_decode(file_get_contents(config('tms.postcode_path')));
            $postcodeList = collect($postcodeList)->groupBy('NAME_CITY');
            foreach ($postcodeList as $k => $v) {
                $key = collect($v)->toArray()[0]->NAME_PROV . collect($v)->toArray()[0]->NAME_CITY;
                foreach (collect($v)->toArray() as $x => $y) {
                    $data[$k][$y->NAME_COUN] = $y->CODE_COUN;
                }
                $value = json_encode($data[$k]);
                Cache::forever($key, $value);
            }
        }
    }
}
