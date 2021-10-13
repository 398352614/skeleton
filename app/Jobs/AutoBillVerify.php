<?php


namespace App\Jobs;


use App\Exceptions\BusinessLogicException;
use App\Models\Merchant;
use App\Services\Admin\BillVerifyService;
use App\Traits\FactoryInstanceTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AutoBillVerify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;


    public $connection = 'redis';

    /**
     * 任务发送到的队列的名称.
     *
     * @var string|null
     */
    public $queue = 'auto-bill-verify';

    /**
     * 任务可以执行的最大秒数 (超时时间)。
     *
     * @var int
     */
    public $timeout = 2;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 1;

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
        Log::info('job start');
        $billVerifyService = FactoryInstanceTrait::getInstance(BillVerifyService::class);
        /** @var $billVerifyService BillVerifyService */
        try {
            Log::info('job start1');
            $billVerifyService->autoStore($this->merchantId);
        } catch (BusinessLogicException $e) {
            Log::channel('job')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        }
        $row = Merchant::query()->where('id', $this->merchantId)->update(['last_settlement_date' => today()->format('Y-m-d')]);
    }

}
