<?php


namespace App\Http\Controllers\Api\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Models\MerchantGroup;
use App\Services\Merchant\MerchantService;

/**
 * Class BatchExceptionController
 * @package App\Http\Controllers\Api\Admin
 * @property MerchantService $service
 */
class MerchantController extends BaseController
{
    public function __construct(MerchantService $service)
    {
        parent::__construct($service);
    }

   public function update(){
        return $this->service->update(['id'=>auth()->user()->id],$this->data);
   }
}
