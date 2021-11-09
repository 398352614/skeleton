<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Models\Journal;
use App\Services\BaseConstService;
use App\Traits\UserTrait;


class JournalService extends BaseService
{

    /**
     * @var \string[][]
     */
    public $filterRules = [
        'create_date' => ['between', ['begin_date', 'end_date']],
        'user_type' => ['=', 'user_type'],
        'verify_status' => ['=', 'verify_status'],
        'mode' => ['=', 'mode']
    ];

    public $orderBy = [
        'id' => 'desc'
    ];

    /**
     * AddressService constructor.
     * @param Journal $model
     */
    public function __construct(Journal $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['journal_no'] = $this->getOrderNoRuleService()->createJournalNo();
        $bill = parent::create($params);
        if ($bill === false) {
            throw new BusinessLogicException('新增失败');
        }
    }

    public function getPageList()
    {
        if (!empty($this->formData['code'])) {
            $where['code'] = $this->formData['code'];
        }
        if (!empty($where)) {
            $merchantList = $this->getMerchantService()->getList($where, ['*'], false);
            $this->query->whereIn('payer_id', $merchantList->pluck('id')->toArray());
            $this->query->orderByDesc('id');
        }
        $data = parent::getPageList();
        foreach ($data as $k => $v) {
            $user = UserTrait::get($v['payer_id'], $v['payer_type']);
            if(!empty($user) && $user['user_type'] == BaseConstService::USER_MERCHANT){
                $data[$k]['code'] = $user['code'];
                $data[$k]['merchant_group_name'] = $this->getMerchantGroupService()->getInfo(['id' => $user['merchant_group_id']], ['*'], false)['name'] ?? '';
            }
        }
        return $data;
    }

    /**
     * @param array $data
     * @throws BusinessLogicException
     */
    public function record(array $data)
    {
        self::store($data);
    }


}
