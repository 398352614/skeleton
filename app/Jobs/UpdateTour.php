<?php

namespace App\Jobs;

use App\Exceptions\BusinessLogicException;
use App\Services\Driver\TourService;
use App\Traits\CompanyTrait;
use App\Traits\FactoryInstanceTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateTour implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * 任务连接名称。
     *
     * @var string|null
     */
    public $connection = 'redis';

    /**
     * 任务发送到的队列的名称.
     *
     * @var string|null
     */
    public $queue = 'update-tour';

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
    public $tries = 3;

    public $tour_no;

    private $batch_ids;


    /**
     * UpdateLineCountTime constructor.
     * @param $tourNo
     * @param $batchIds
     */
    public function __construct($tourNo, $batchIds)
    {
        $this->tour_no = $tourNo;
        $this->batch_ids = $batchIds;
    }


    /**
     *
     * @throws \Throwable
     */
    public function handle()
    {
        try {
            $tour = DB::table('tour')->where('tour_no', $this->tour_no)->first();
            $company = CompanyTrait::getCompany($tour->company_id);
            request()->headers->set('X-Uuid', $company['company_code']);
            /** @var  TourService $tourService */
            $tourService = FactoryInstanceTrait::getInstance(TourService::class);
            $tourService->updateBatchIndex(['tour_no' => $this->tour_no, 'batch_ids' => $this->batch_ids]);
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
    }
}
