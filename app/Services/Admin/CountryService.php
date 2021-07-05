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
use App\Traits\CountryTrait;
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
        $info = parent::getInfo([], ['id'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('已添加了国家，不能再次添加国家');
        }
        $country = CountryTrait::getCountry($params['short']);
        if (empty($country)) {
            throw new BusinessLogicException('国家不存在');
        }
        $rowCount = parent::create($country);
        if ($rowCount === false) {
            throw new BusinessLogicException('国家新增失败');
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
        $order = $this->getOrderService()->getInfo([], ['id'], false);
        if (!empty($order)) {
            throw new BusinessLogicException('已存在订单，不能删除国家');
        }
        $place = $this->getAddressService()->getInfo([], ['id'], false);
        if (!empty($place)) {
            throw new BusinessLogicException('已存在收件人，不能删除国家');
        }
        $warehouse = $this->getWareHouseService()->getInfo([], ['id'], false);
        if (!empty($warehouse)) {
            throw new BusinessLogicException('已存在网点，不能删除国家');
        }
        $line = $this->getLineService()->getInfo([], ['id'], false);
        if (!empty($line)) {
            throw new BusinessLogicException('已存在线路，不能删除国家');
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
        Artisan::call('cache:company --company_id=' . auth()->user()->company_id);
    }
}
