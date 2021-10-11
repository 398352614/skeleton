<?php


namespace App\Jobs;


use App\Models\BillVerify;
use App\Models\Merchant;
use App\Services\Admin\BillVerifyService;
use App\Services\Admin\TourService;
use App\Traits\FactoryInstanceTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class AutoBillVerify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;


    public $connection = 'redis';

    /**
     * 任务发送到的队列的名称.
     *
     * @var string|null
     */
    public $queue = 'bill-verify';

    /**
     * 任务可以执行的最大秒数 (超时时间)。
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 0;

    private $merchantId;

    /**
     * addTrail constructor.
     * @param $merchantId
     */
    public function __construct($merchantId)
    {
        $this->merchantId = $merchantId;
    }


    /**
     * 触发入库分拣队列
     * Execute the job.
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function handle()
    {
        $billVerifyService = FactoryInstanceTrait::getInstance(BillVerifyService::class);
        /** @var $billVerifyService BillVerifyService*/
        try {
            $billVerifyService->autoStore($this->merchantId);
        }catch (Exception $e){
            Log::channel('job')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        }
    }

}
