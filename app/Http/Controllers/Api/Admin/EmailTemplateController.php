<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 4/19/2021
 * Time : 2:17 PM
 * Email: i@nizer.in
 * Blog : nizer.in
 * FileName: EmailTemplateController.php
 */


namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\EmailTemplateService;

/**
 * Class EmailTemplateController
 * @package App\Http\Controllers\Api\Admin
 */
class EmailTemplateController extends BaseController
{
    /**
     * EmailTemplateController constructor.
     * @param  EmailTemplateService  $service
     * @param  array  $exceptMethods
     */
    public function __construct(EmailTemplateService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        return $this->service->create($this->data);
    }

    /**
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function detail($id)
    {
        return $this->service->getInfo(['id' => $id], ['*'], false);
    }

    /**
     * @param $id
     * @return int
     */
    public function update($id)
    {
        return $this->service->update(['id' => $id], $this->data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->service->delete(['id' => $id]);
    }
}
