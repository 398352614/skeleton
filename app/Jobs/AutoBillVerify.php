<?php


namespace App\Jobs;


use App\Exceptions\BusinessLogicException;
use App\Models\Employee;
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
        Log::notice(1);
        $this->merchantId = $merchantId;
        Log::notice(2);
    }


    /**
     * 触发入库分拣队列
     * Execute the job.
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function handle()
    {
        Log::info('job start');
        $merchant = Merchant::query()->where('id', $this->merchantId)->first('company_id');
        $employee = Employee::query()->where('id', $merchant['company_id'])->first();
        auth()->setUser($employee);
        Log::info(auth()->user()->company_id);
        try {
            Log::info(4);
            $billVerifyService = FactoryInstanceTrait::getInstance(BillVerifyService::class);
            /** @var $billVerifyService BillVerifyService */
            Log::info(5);
            $billVerifyService->autoStore($this->merchantId);
            Log::info(6);
        } catch (BusinessLogicException $e) {
            Log::channel('job')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'BusinessLogicException', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            Log::channel('job')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        }
        Merchant::query()->where('id', $this->merchantId)->update(['last_settlement_date' => today()->format('Y-m-d')]);
        Log::info(8);
    }

}
