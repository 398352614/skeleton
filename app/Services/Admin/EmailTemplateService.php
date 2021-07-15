<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 4/19/2021
 * Time : 2:16 PM
 * Email: i@nizer.in
 * Blog : nizer.in
 * FileName: EmailTemplateService.php
 */


namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\EmailTemplateResource;
use App\Models\EmailTemplate;

/**
 * Class EmailTemplateService
 * @package App\Services\Admin
 */
class EmailTemplateService extends BaseService
{
    /**
     * @var string[]
     */
    public $orderBy = [
        'id' => 'desc'
    ];

    /**
     * EmailTemplateService constructor.
     * @param  EmailTemplate  $model
     * @param  null  $infoResource
     */
    public function __construct(EmailTemplate $model, $infoResource = null)
    {
        parent::__construct($model, EmailTemplateResource::class, $infoResource);
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws BusinessLogicException
     */
    public function create($data)
    {
        $check = $this->query->where('type', $data['type'])->first();

        if (!empty($check)) {
            throw new BusinessLogicException(__('该类型的邮件模板已添加'));
        }

        return parent::create($data);
    }
}
