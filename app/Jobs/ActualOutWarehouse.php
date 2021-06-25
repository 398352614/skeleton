<?php

namespace App\Jobs;

use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Models\Tour;
use App\Services\Admin\TourService;
use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use App\Traits\FactoryInstanceTrait;
use App\Traits\TourTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActualOutWarehouse implements ShouldQueue
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
    public $queue = 'actual-out';

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
    private $trackingOrderList;


    /**
     * UpdateLineCountTime constructor.
     * @param $tourNo
     * @param $trackingOrderList
     */
    public function __construct($tourNo, $trackingOrderList)
    {
        $this->tour_no = $tourNo;
        $this->trackingOrderList = $trackingOrderList;
    }


    /**
     * @return bool
     * @throws \Throwable
     */
    public function handle()
    {
        Log::channel('job')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '确认出库开始');
        try {
            /*****************************************1.智能调度*******************************************************/
            $tour = DB::table('tour')->where('tour_no', $this->tour_no)->first();
            $company = CompanyTrait::getCompany($tour->company_id);
            request()->headers->set('X-Uuid', $company['company_code']);
            /**@var TourService $tourService */
            $tourService = FactoryInstanceTrait::getInstance(TourService::class);
            $batchList = Batch::query()->where('tour_no', $this->tour_no)->whereIn('status', [BaseConstService::BATCH_CANCEL, BaseConstService::BATCH_CHECKOUT])->get(['id', 'sort_id'])->toArray();
            $ingBatchList = Batch::query()->where('tour_no', $this->tour_no)->whereNotIn('status', [BaseConstService::BATCH_CANCEL, BaseConstService::BATCH_CHECKOUT])->orderBy('sort_id')->get(['id', 'sort_id'])->toArray();
            $batchList = array_merge($batchList, $ingBatchList);
            $sortBatch = Arr::first($batchList, function ($batch) {
                return $batch['sort_id'] != 1000;
            });
            try {
                if (!empty($sortBatch)) {
                    Log::channel('job')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'batch_ids', array_column($batchList, 'id'));
                    $tourService->updateBatchIndex(['tour_no' => $this->tour_no, 'batch_ids' => array_column($batchList, 'id')]);
                } else {
                    $tourService->autoOpTour(['tour_no' => $this->tour_no]);
                }
            } catch (BusinessLogicException $e) {
                Log::channel('job')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'BusinessLogicException', [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage()
                ]);
            }
            Log::channel('job')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '确认出库成功');
        } catch (\Exception $e) {
            Log::channel('job')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        }
        //通知出库
        $tour = Tour::query()->where('tour_no', $this->tour_no)->first()->toArray();
        $batchList = Batch::query()->where('tour_no', $this->tour_no)->where('status', BaseConstService::BATCH_DELIVERING)->get()->toArray();
        event(new \App\Events\TourNotify\ActualOutWarehouse($tour, $batchList, $this->trackingOrderList));
        /**************************************3.通知下一个站点事件************************************************/
        $tour = Tour::query()->where('tour_no', $this->tour_no)->first()->toArray();
        $nextBatch = TourTrait::getNextBatch($tour['tour_no']);
        if (!empty($nextBatch)) {
            event(new \App\Events\TourNotify\NextBatch($tour, $nextBatch->toArray()));
        }
        return true;
    }
}
