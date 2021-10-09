<?php
/**
 * 国家 服务
 * User: long
 * Date: 2019/12/26
 * Time: 15:56
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\CountryResource;
use App\Models\Country;
use App\Services\BaseConstService;
use App\Traits\CountryTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class CountryService extends BaseService
{
    public function __construct(Country $country)
    {
        parent::__construct($country, CountryResource::class);
    }

    /**
     * 列表查询
     * @return array
     */
    public function index()
    {
        $list = parent::getList([], ['*'], false)->toArray();
        $list = $this->locateCountryList($list);
        return $list;
    }

    /**
     * 新增初始化
     * @return array
     */
    public function initStore()
    {
        $data = [];
        $countryList = array_values(CountryTrait::getCountryList());
        $dbCountryList = parent::getList([], ['*'], false);
        $countryList = array_values(collect($countryList)->whereNotIn('short', $dbCountryList->pluck('short')->toArray())->all());
        $data['country_list'] = $this->locateCountryList($countryList);;
        return $data;
    }

    private function locateCountryList($countryList)
    {
        //获取语言
        $locate = (App::getLocale() !== 'cn') ? 'en' : 'cn';
        //获取字段
        $columnName = $locate . '_name';
        $delColumnName = ($columnName === 'en_name') ? 'en_name' : 'en_name';
        //字段处理
        $countryList = array_map(function ($country) use ($columnName, $delColumnName) {
            $country['name'] = $country[$columnName];
            unset($country[$columnName], $country[$delColumnName]);
            return $country;
        }, $countryList);
        return $countryList;
    }


    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        //判断是否已经存在国家
        $info = parent::getInfo(['short' => $params['short']], ['id'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('已添加了国家，不能再次添加国家');
        }
        $count = parent::count();
        dd(auth()->user()->company_id, config('tms.test_company_id'));
        if ($count >= 3 && (auth()->user()->company_id != config('tms.test_company_id'))) {
            throw new BusinessLogicException('最多添加三个国家');
        }
        $country = CountryTrait::getCountry($params['short']);
        if (empty($country)) {
            throw new BusinessLogicException('国家不存在');
        }
        if ($count == 0) {
            $country['is_default'] = BaseConstService::YES;
        }
        $rowCount = parent::create($country);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败');
        }
        Artisan::call('cache:company --company_id=' . auth()->user()->company_id);
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $country = parent::getInfo(['id' => $id], ['*'], false);
        if (!empty($country)) {
            $order = $this->getOrderService()->getList(['place_country' => $country['short']], ['id'], false)->toArray();
            if (!empty($order)) {
                throw new BusinessLogicException('系统中已维护该国家，不能删除');
                //throw new BusinessLogicException('已存在订单，不能删除国家');
            }
            $place = $this->getAddressService()->getInfo(['place_country' => $country['short']], ['id'], false);
            if (!empty($place)) {
                throw new BusinessLogicException('系统中已维护该国家，不能删除');
                //throw new BusinessLogicException('已存在收件人，不能删除国家');
            }
            $warehouse = $this->getWareHouseService()->getInfo(['country' => $country['short']], ['id'], false);
            if (!empty($warehouse)) {
                throw new BusinessLogicException('系统中已维护该国家，不能删除');
                //throw new BusinessLogicException('已存在网点，不能删除国家');
            }
            $rowCount = parent::delete(['id' => $id]);
            if ($rowCount === false) {
                throw new BusinessLogicException('删除失败，请重新操作');
            }
        }
        Artisan::call('cache:company --company_id=' . auth()->user()->company_id);
    }

    /**
     * 修改默认值
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $data = Arr::only($data, 'is_default');
        $row = parent::updateById($id, $data);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
    }
}
