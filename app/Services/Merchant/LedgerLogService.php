<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Models\Ledger;
use App\Models\LedgerLog;
use App\Services\BaseConstService;
use App\Traits\UserTrait;
use Illuminate\Support\Facades\Log;

class LedgerLogService extends BaseService
{
    /**
     * @var \string[][]
     */
    public $filterRules = [
        'create_date' => ['between', ['begin_date', 'end_date']],
        'user_type' => ['=', 'user_type'],
    ];


    /**
     * AddressService constructor.
     * @param Ledger $model
     */
    public function __construct(LedgerLog $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @throws BusinessLogicException
     */
    public function log(array $data)
    {
        $data['ledger_id'] = $data['id'];
        $ledger=$this->getLedgerService()->getInfo(['id'=>$data['id']],['*'],false);
        if(empty($ledger)){
            throw new BusinessLogicException('数据不存在');
        }
        unset($data['created_at'], $data['updated_at'], $data['id']);
        $user = UserTrait::get($ledger['user_id'], $ledger['user_type']);
        $data['user_name'] = $user['name'];
        $data['user_code'] = $user['code'];
        $data['operator_id'] = auth()->user()->id;
        $data['operator_type'] = BaseConstService::USER_ADMIN;
        $data['operator_name'] = auth()->user()->username;
        $row = parent::create($data);
        if ($row == false) {
            Log::channel('info')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '记录货主财务账户修改日志失败');
        }
    }

}
