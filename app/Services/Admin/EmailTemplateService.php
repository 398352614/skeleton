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
}
