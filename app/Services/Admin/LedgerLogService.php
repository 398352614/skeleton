<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\AddressResource;
use App\Http\Validate\Api\Admin\AddressImportValidate;
use App\Http\Validate\BaseValidate;
use App\Models\Address;
use App\Models\Ledger;
use App\Models\LedgerLog;
use App\Services\BaseConstService;
use App\Services\CommonService;
use App\Traits\AddressTrait;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ExportTrait;
use App\Traits\ImportTrait;
use App\Traits\LocationTrait;
use App\Traits\UserTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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

    public function log(array $data)
    {
        $data['ledger_id'] = $data['id'];
        unset($data['created_at'], $data['updated_at'], $data['id']);
        $user = UserTrait::get($data['user_id'], $data['user_type']);
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
